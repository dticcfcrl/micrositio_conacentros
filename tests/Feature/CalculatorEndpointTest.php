<?php

namespace Tests\Feature;

use App\Http\Middleware\VerifyCsrfToken;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class CalculatorEndpointTest extends TestCase
{
    use DatabaseTransactions;

    private const RUTA = '/resultado-calcular-prestaciones';

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(VerifyCsrfToken::class);
    }

    private function payloadCasoIssue(array $overrides = []): array
    {
        return array_merge([
            'remuneracion' => 700,
            'periodicidad_id' => 4,
            'fecha_ingreso' => '2012-01-01',
            'fecha_salida' => '2026-04-09',
            'zona' => 0,
            'ocupacion_id' => null,
        ], $overrides);
    }

    private function moneyToFloat(string $valor): float
    {
        return (float) str_replace([',', '$'], '', $valor);
    }

    private function asArrayDeFloats(array $bloque): array
    {
        $out = [];
        foreach ($bloque as $k => $v) {
            $out[$k] = is_string($v) ? $this->moneyToFloat($v) : $v;
        }
        return $out;
    }

    public function test_post_caso_del_issue_devuelve_estructura_esperada(): void
    {
        $response = $this->postJson(self::RUTA, $this->payloadCasoIssue());

        $response->assertOk();
        $data = $response->json();

        $this->assertArrayNotHasKey('error', $data, 'El endpoint no debería devolver error con datos válidos');

        foreach (['completa', 'al90', 'al80', 'al70', 'al60', 'al50'] as $key) {
            $this->assertArrayHasKey($key, $data, "Falta la propuesta {$key}");
            foreach (['indemnizacion', 'aguinaldo', 'vacaciones', 'prima_vacacional', 'prima_antiguedad', 'total'] as $concepto) {
                $this->assertArrayHasKey($concepto, $data[$key], "Falta el concepto {$concepto} en {$key}");
            }
        }
    }

    public function test_post_caso_del_issue_aplica_fix_y_al_90_supera_los_140_mil(): void
    {
        $response = $this->postJson(self::RUTA, $this->payloadCasoIssue());

        $data = $response->json();
        $al90 = $this->asArrayDeFloats($data['al90']);

        $this->assertGreaterThan(140000, $al90['total'], 'Antes del fix, al90 quedaba alrededor de 122 mil; después del fix debe superar los 140 mil');
        $this->assertLessThan(170000, $al90['total'], 'al90 no debe exceder el rango razonable post-fix');
    }

    public function test_invariante_irrenunciables_no_varian_entre_propuestas_via_endpoint(): void
    {
        $response = $this->postJson(self::RUTA, $this->payloadCasoIssue());
        $data = $response->json();

        $completa = $this->asArrayDeFloats($data['completa']);

        foreach (['al90', 'al80', 'al70', 'al60', 'al50'] as $key) {
            $bloque = $this->asArrayDeFloats($data[$key]);
            $this->assertEqualsWithDelta($completa['aguinaldo'], $bloque['aguinaldo'], 0.01, "Aguinaldo varió en {$key}");
            $this->assertEqualsWithDelta($completa['vacaciones'], $bloque['vacaciones'], 0.01, "Vacaciones variaron en {$key}");
            $this->assertEqualsWithDelta($completa['prima_vacacional'], $bloque['prima_vacacional'], 0.01, "Prima vacacional varió en {$key}");
        }
    }

    public function test_invariante_indemnizacion_escala_lineal_via_endpoint(): void
    {
        $response = $this->postJson(self::RUTA, $this->payloadCasoIssue());
        $data = $response->json();

        $indemnCompleta = $this->moneyToFloat($data['completa']['indemnizacion']);

        foreach ([90, 80, 70, 60, 50] as $porcentaje) {
            $obtenido = $this->moneyToFloat($data['al' . $porcentaje]['indemnizacion']);
            $esperado = $indemnCompleta * ($porcentaje / 100);
            $this->assertEqualsWithDelta($esperado, $obtenido, 0.05, "Indemnización al {$porcentaje}% no escala lineal");
        }
    }

    public function test_invariante_propuesta_total_iguala_irrenunciables_mas_porcentaje_por_negociables_cuando_menor_a_15(): void
    {
        $response = $this->postJson(self::RUTA, $this->payloadCasoIssue());
        $data = $response->json();

        $completa = $this->asArrayDeFloats($data['completa']);
        $irrenunciables = $completa['aguinaldo'] + $completa['vacaciones'] + $completa['prima_vacacional'];
        $negociables = $completa['indemnizacion'] + $completa['prima_antiguedad'];

        foreach ([90, 80, 70, 60, 50] as $porcentaje) {
            $bloque = $this->asArrayDeFloats($data['al' . $porcentaje]);
            $esperado = $irrenunciables + ($porcentaje / 100) * $negociables;
            $this->assertEqualsWithDelta($esperado, $bloque['total'], 0.05, "Total al {$porcentaje}% no cumple la fórmula con prima negociable");
        }
    }

    public function test_post_con_antiguedad_mayor_o_igual_a_15_mantiene_prima_constante(): void
    {
        $response = $this->postJson(self::RUTA, $this->payloadCasoIssue([
            'fecha_ingreso' => '2010-01-01',
        ]));

        $data = $response->json();
        $primaCompleta = $this->moneyToFloat($data['completa']['prima_antiguedad']);

        foreach (['al90', 'al80', 'al70', 'al60', 'al50'] as $key) {
            $primaPropuesta = $this->moneyToFloat($data[$key]['prima_antiguedad']);
            $this->assertEqualsWithDelta($primaCompleta, $primaPropuesta, 0.01, "Prima en {$key} debería ser constante con antigüedad mayor o igual a 15");
        }
    }

    public function test_post_con_antiguedad_mayor_o_igual_a_15_solo_escala_indemnizacion(): void
    {
        $response = $this->postJson(self::RUTA, $this->payloadCasoIssue([
            'fecha_ingreso' => '2010-01-01',
        ]));

        $data = $response->json();
        $completa = $this->asArrayDeFloats($data['completa']);

        $base = $completa['aguinaldo'] + $completa['vacaciones'] + $completa['prima_vacacional'] + $completa['prima_antiguedad'];
        $indemnCompleta = $completa['indemnizacion'];

        foreach ([90, 80, 70, 60, 50] as $porcentaje) {
            $bloque = $this->asArrayDeFloats($data['al' . $porcentaje]);
            $esperado = $base + ($porcentaje / 100) * $indemnCompleta;
            $this->assertEqualsWithDelta($esperado, $bloque['total'], 0.05, "Total al {$porcentaje}% no cumple la fórmula con prima no negociable");
        }
    }

    public function test_regresion_al_50_y_al_100_no_cambian_con_el_fix(): void
    {
        $response = $this->postJson(self::RUTA, $this->payloadCasoIssue());
        $data = $response->json();

        $completa = $this->asArrayDeFloats($data['completa']);
        $al50 = $this->asArrayDeFloats($data['al50']);

        $irrenunciables = $completa['aguinaldo'] + $completa['vacaciones'] + $completa['prima_vacacional'];
        $negociables = $completa['indemnizacion'] + $completa['prima_antiguedad'];

        $this->assertEqualsWithDelta($irrenunciables + $negociables, $completa['total'], 0.05);
        $this->assertEqualsWithDelta($irrenunciables + 0.5 * $negociables, $al50['total'], 0.05);
    }

    public function test_endpoint_devuelve_strings_formateados_como_moneda(): void
    {
        $response = $this->postJson(self::RUTA, $this->payloadCasoIssue());
        $data = $response->json();

        $this->assertMatchesRegularExpression('/^\$[\d,]+\.\d{2}$/', $data['al90']['total']);
        $this->assertMatchesRegularExpression('/^\$[\d,]+\.\d{2}$/', $data['completa']['indemnizacion']);
    }

    public function test_post_caso_qa_vacaciones_son_19232_exactos(): void
    {
        $response = $this->postJson(self::RUTA, [
            'remuneracion'    => 1000,
            'periodicidad_id' => 4,
            'fecha_ingreso'   => '2020-12-16',
            'fecha_salida'    => '2025-12-01',
            'zona'            => 0,
            'ocupacion_id'    => null,
        ]);

        $response->assertOk();
        $data = $response->json();

        $this->assertArrayNotHasKey('error', $data, 'El endpoint no debería devolver error con el caso de QA');
        $this->assertSame('$19,232.00', $data['completa']['vacaciones']);
    }

    public function test_post_borde_antiguedad_justo_por_debajo_de_15_va_por_rama_negociable(): void
    {
        // 5474 días entre 2011-01-01 y 2025-12-27 → 14.99726 años → round 4 dec = 14.9973 → < 15
        $response = $this->postJson(self::RUTA, $this->payloadCasoIssue([
            'fecha_ingreso' => '2011-01-01',
            'fecha_salida'  => '2025-12-27',
        ]));

        $response->assertOk();
        $data = $response->json();

        $primaCompleta = $this->moneyToFloat($data['completa']['prima_antiguedad']);
        $prima50 = $this->moneyToFloat($data['al50']['prima_antiguedad']);

        $this->assertEqualsWithDelta($primaCompleta * 0.5, $prima50, 0.05, 'Prima al 50% debe escalar linealmente (rama negociable cuando antigüedad < 15)');
    }

    public function test_post_borde_antiguedad_exactamente_15_va_por_rama_no_negociable(): void
    {
        // 5475 días entre 2011-01-01 y 2025-12-28 → 15.00000 años → round 4 dec = 15.0000 → >= 15
        $response = $this->postJson(self::RUTA, $this->payloadCasoIssue([
            'fecha_ingreso' => '2011-01-01',
            'fecha_salida'  => '2025-12-28',
        ]));

        $response->assertOk();
        $data = $response->json();

        $primaCompleta = $this->moneyToFloat($data['completa']['prima_antiguedad']);
        $prima50 = $this->moneyToFloat($data['al50']['prima_antiguedad']);

        $this->assertEqualsWithDelta($primaCompleta, $prima50, 0.05, 'Prima al 50% debe mantenerse igual (rama no negociable cuando antigüedad >= 15)');
    }
}
