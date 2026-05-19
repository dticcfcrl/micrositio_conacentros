<?php

namespace Tests\Unit\Services;

use App\Services\LiquidacionCalculator;
use PHPUnit\Framework\TestCase;

class LiquidacionCalculatorTest extends TestCase
{
    private LiquidacionCalculator $calculator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->calculator = new LiquidacionCalculator();
    }

    private function inputsBase(array $overrides = []): array
    {
        return array_merge([
            'anios_antiguedad' => 10.0,
            'propVacaciones' => 1.0,
            'remuneracionDiaria' => 500.0,
            'salarioMinimo' => 200.0,
            'propAguinaldo' => 1.0,
            'anios_antiguedad_int' => 10,
            'diasVacaciones' => 12,
        ], $overrides);
    }

    public function test_estructura_de_la_respuesta_incluye_completa_y_propuestas_escaladas(): void
    {
        $resultado = $this->calculator->calcular($this->inputsBase());

        $this->assertArrayHasKey('completa', $resultado);
        $this->assertArrayHasKey('al90', $resultado);
        $this->assertArrayHasKey('al80', $resultado);
        $this->assertArrayHasKey('al70', $resultado);
        $this->assertArrayHasKey('al60', $resultado);
        $this->assertArrayHasKey('al50', $resultado);
        $this->assertArrayHasKey('anios_antiguedad', $resultado);

        foreach (['indemnizacion', 'aguinaldo', 'vacaciones', 'prima_vacacional', 'prima_antiguedad', 'total'] as $concepto) {
            $this->assertArrayHasKey($concepto, $resultado['al90'], "Falta {$concepto} en al90");
            $this->assertArrayHasKey($concepto, $resultado['completa'], "Falta {$concepto} en completa");
        }
        $this->assertArrayHasKey('gratificacion_b', $resultado['completa']);
    }

    public function test_caso_redondo_antiguedad_menor_a_15_calcula_completa_correctamente(): void
    {
        $resultado = $this->calculator->calcular($this->inputsBase());

        $this->assertEqualsWithDelta(47219.18, $resultado['completa']['indemnizacion'], 0.01);
        $this->assertEqualsWithDelta(7500.00, $resultado['completa']['aguinaldo'], 0.01);
        $this->assertEqualsWithDelta(6000.00, $resultado['completa']['vacaciones'], 0.01);
        $this->assertEqualsWithDelta(1500.00, $resultado['completa']['prima_vacacional'], 0.01);
        $this->assertEqualsWithDelta(48000.00, $resultado['completa']['prima_antiguedad'], 0.01);
        $this->assertEqualsWithDelta(110219.18, $resultado['completa']['total'], 0.01);
    }

    public function test_invariante_irrenunciables_no_escalan_entre_propuestas(): void
    {
        $resultado = $this->calculator->calcular($this->inputsBase());

        $aguinaldo = $resultado['completa']['aguinaldo'];
        $vacaciones = $resultado['completa']['vacaciones'];
        $primaVacacional = $resultado['completa']['prima_vacacional'];

        foreach (['al90', 'al80', 'al70', 'al60', 'al50'] as $key) {
            $this->assertEqualsWithDelta($aguinaldo, $resultado[$key]['aguinaldo'], 0.01, "Aguinaldo en {$key} no debería variar");
            $this->assertEqualsWithDelta($vacaciones, $resultado[$key]['vacaciones'], 0.01, "Vacaciones en {$key} no deberían variar");
            $this->assertEqualsWithDelta($primaVacacional, $resultado[$key]['prima_vacacional'], 0.01, "Prima vacacional en {$key} no debería variar");
        }
    }

    public function test_invariante_indemnizacion_escala_lineal_con_porcentaje(): void
    {
        $resultado = $this->calculator->calcular($this->inputsBase());

        $indemnCompleta = $resultado['completa']['indemnizacion'];
        $porcentajes = [90, 80, 70, 60, 50];

        foreach ($porcentajes as $porcentaje) {
            $esperado = $indemnCompleta * ($porcentaje / 100);
            $obtenido = $resultado['al' . $porcentaje]['indemnizacion'];
            $this->assertEqualsWithDelta($esperado, $obtenido, 0.01, "Indemnización al {$porcentaje}% no escala lineal");
        }
    }

    public function test_invariante_prima_antiguedad_escala_lineal_cuando_menor_a_15(): void
    {
        $resultado = $this->calculator->calcular($this->inputsBase(['anios_antiguedad' => 14.999, 'anios_antiguedad_int' => 14]));

        $primaCompleta = $resultado['completa']['prima_antiguedad'];
        $porcentajes = [90, 80, 70, 60, 50];

        foreach ($porcentajes as $porcentaje) {
            $esperado = $primaCompleta * ($porcentaje / 100);
            $obtenido = $resultado['al' . $porcentaje]['prima_antiguedad'];
            $this->assertEqualsWithDelta($esperado, $obtenido, 0.01, "Prima al {$porcentaje}% no escala lineal con antigüedad menor a 15");
        }
    }

    public function test_invariante_prima_antiguedad_constante_cuando_mayor_o_igual_a_15(): void
    {
        $resultado = $this->calculator->calcular($this->inputsBase(['anios_antiguedad' => 16.0, 'anios_antiguedad_int' => 16]));

        $primaCompleta = $resultado['completa']['prima_antiguedad'];

        foreach (['al90', 'al80', 'al70', 'al60', 'al50'] as $key) {
            $this->assertEqualsWithDelta($primaCompleta, $resultado[$key]['prima_antiguedad'], 0.01, "Prima en {$key} debería permanecer al 100% con antigüedad mayor o igual a 15");
        }
    }

    public function test_borde_antiguedad_exactamente_15_va_por_rama_no_negociable(): void
    {
        $resultado = $this->calculator->calcular($this->inputsBase(['anios_antiguedad' => 15.0, 'anios_antiguedad_int' => 15]));

        $primaCompleta = $resultado['completa']['prima_antiguedad'];

        $this->assertEqualsWithDelta($primaCompleta, $resultado['al90']['prima_antiguedad'], 0.01);
        $this->assertEqualsWithDelta($primaCompleta, $resultado['al50']['prima_antiguedad'], 0.01);
    }

    public function test_borde_antiguedad_apenas_menor_a_15_va_por_rama_negociable(): void
    {
        $resultado = $this->calculator->calcular($this->inputsBase(['anios_antiguedad' => 14.999, 'anios_antiguedad_int' => 14]));

        $primaCompleta = $resultado['completa']['prima_antiguedad'];

        $this->assertEqualsWithDelta($primaCompleta * 0.9, $resultado['al90']['prima_antiguedad'], 0.01);
        $this->assertEqualsWithDelta($primaCompleta * 0.5, $resultado['al50']['prima_antiguedad'], 0.01);
    }

    public function test_salario_topado_se_aplica_cuando_remuneracion_supera_dos_veces_salario_minimo(): void
    {
        $resultado = $this->calculator->calcular($this->inputsBase([
            'remuneracionDiaria' => 1000.0,
            'salarioMinimo' => 200.0,
            'anios_antiguedad' => 10.0,
            'anios_antiguedad_int' => 10,
        ]));

        $primaEsperada = (2 * 200.0) * 10.0 * 12;
        $this->assertEqualsWithDelta($primaEsperada, $resultado['completa']['prima_antiguedad'], 0.01);
    }

    public function test_sin_tope_cuando_remuneracion_es_menor_o_igual_a_dos_veces_salario_minimo(): void
    {
        $resultado = $this->calculator->calcular($this->inputsBase([
            'remuneracionDiaria' => 300.0,
            'salarioMinimo' => 200.0,
            'anios_antiguedad' => 10.0,
            'anios_antiguedad_int' => 10,
        ]));

        $primaEsperada = 300.0 * 10.0 * 12;
        $this->assertEqualsWithDelta($primaEsperada, $resultado['completa']['prima_antiguedad'], 0.01);
    }

    public function test_invariante_total_es_suma_de_componentes(): void
    {
        $resultado = $this->calculator->calcular($this->inputsBase());

        foreach (['completa', 'al90', 'al80', 'al70', 'al60', 'al50'] as $key) {
            $suma = $resultado[$key]['indemnizacion']
                  + $resultado[$key]['aguinaldo']
                  + $resultado[$key]['vacaciones']
                  + $resultado[$key]['prima_vacacional']
                  + $resultado[$key]['prima_antiguedad'];
            $this->assertEqualsWithDelta($suma, $resultado[$key]['total'], 0.02, "Total en {$key} no coincide con la suma de componentes");
        }
    }

    public function test_invariante_propuesta_escalada_iguala_irrenunciables_mas_porcentaje_por_negociables_cuando_menor_a_15(): void
    {
        $resultado = $this->calculator->calcular($this->inputsBase());

        $irrenunciables = $resultado['completa']['aguinaldo']
                        + $resultado['completa']['vacaciones']
                        + $resultado['completa']['prima_vacacional'];

        $negociables = $resultado['completa']['indemnizacion']
                     + $resultado['completa']['prima_antiguedad'];

        foreach ([90, 80, 70, 60, 50] as $porcentaje) {
            $esperado = $irrenunciables + ($porcentaje / 100) * $negociables;
            $obtenido = $resultado['al' . $porcentaje]['total'];
            $this->assertEqualsWithDelta($esperado, $obtenido, 0.05, "Total al {$porcentaje}% no cumple la fórmula esperada");
        }
    }

    public function test_invariante_propuesta_escalada_cuando_mayor_o_igual_a_15_solo_escala_indemnizacion(): void
    {
        $resultado = $this->calculator->calcular($this->inputsBase(['anios_antiguedad' => 16.0, 'anios_antiguedad_int' => 16]));

        $base = $resultado['completa']['aguinaldo']
              + $resultado['completa']['vacaciones']
              + $resultado['completa']['prima_vacacional']
              + $resultado['completa']['prima_antiguedad'];

        $indemnCompleta = $resultado['completa']['indemnizacion'];

        foreach ([90, 80, 70, 60, 50] as $porcentaje) {
            $esperado = $base + ($porcentaje / 100) * $indemnCompleta;
            $obtenido = $resultado['al' . $porcentaje]['total'];
            $this->assertEqualsWithDelta($esperado, $obtenido, 0.05, "Total al {$porcentaje}% no cumple la fórmula esperada con prima no negociable");
        }
    }

    public function test_regresion_del_bug_al90_no_es_igual_a_calculo_con_prima_fija_al_50(): void
    {
        $resultado = $this->calculator->calcular($this->inputsBase());

        $indemnCompleta = $resultado['completa']['indemnizacion'];
        $primaCompleta = $resultado['completa']['prima_antiguedad'];
        $irrenunciables = $resultado['completa']['aguinaldo']
                        + $resultado['completa']['vacaciones']
                        + $resultado['completa']['prima_vacacional'];

        $totalBuggy = $irrenunciables + 0.5 * $primaCompleta + 0.9 * $indemnCompleta;
        $totalCorrecto = $irrenunciables + 0.9 * ($primaCompleta + $indemnCompleta);

        $this->assertNotEqualsWithDelta($totalBuggy, $resultado['al90']['total'], 0.01, 'al90 debería NO coincidir con el cálculo bugueado');
        $this->assertEqualsWithDelta($totalCorrecto, $resultado['al90']['total'], 0.05);
    }

    public function test_anios_antiguedad_devueltos_son_los_enteros_pasados_como_input(): void
    {
        $resultado = $this->calculator->calcular($this->inputsBase(['anios_antiguedad_int' => 14]));
        $this->assertSame(14, $resultado['anios_antiguedad']);
    }

    public function test_gratificacion_b_solo_aparece_en_completa(): void
    {
        $resultado = $this->calculator->calcular($this->inputsBase());

        $this->assertArrayHasKey('gratificacion_b', $resultado['completa']);
        foreach (['al90', 'al80', 'al70', 'al60', 'al50'] as $key) {
            $this->assertArrayNotHasKey('gratificacion_b', $resultado[$key], "gratificacion_b no debería estar en {$key}");
        }
    }

    public function test_caso_qa_vacaciones_redondea_proporcion_a_4_decimales(): void
    {
        $resultado = $this->calculator->calcular([
            'anios_antiguedad'     => 4.96164383,
            'propVacaciones'       => 0.96164383,
            'remuneracionDiaria'   => 1000.0,
            'salarioMinimo'        => 315.04,
            'propAguinaldo'        => 0.9205,
            'anios_antiguedad_int' => 4,
            'diasVacaciones'       => 20,
        ]);

        $this->assertSame(19232.00, $resultado['completa']['vacaciones']);
    }
}
