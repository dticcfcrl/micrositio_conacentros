<?php

namespace App\Services;

class LiquidacionCalculator
{
    public function calcular(array $inputs): array
    {
        $anios_antiguedad = $inputs['anios_antiguedad'];
        $propVacaciones = $inputs['propVacaciones'];
        $remuneracionDiaria = $inputs['remuneracionDiaria'];
        $salarioMinimo = $inputs['salarioMinimo'];
        $propAguinaldo = $inputs['propAguinaldo'];
        $anios_antiguedad_int = $inputs['anios_antiguedad_int'];
        $diasVacaciones = $inputs['diasVacaciones'];

        $pagoVacaciones = $propVacaciones * $diasVacaciones * $remuneracionDiaria;
        $salarioTopado = ($remuneracionDiaria > (2 * $salarioMinimo) ? (2 * $salarioMinimo) : $remuneracionDiaria);

        $resultado = [];

        $total = 0;
        $completa['indemnizacion'] = round(($remuneracionDiaria * (1 + (15 / 365) + ($diasVacaciones * .25 / 365))) * 90, 2);
        $total += round(($remuneracionDiaria * (1 + (15 / 365) + ($diasVacaciones * .25 / 365))) * 90, 2);
        $completa['aguinaldo'] = round($remuneracionDiaria * 15 * $propAguinaldo, 2);
        $total += round($remuneracionDiaria * 15 * $propAguinaldo, 2);
        $completa['vacaciones'] = round($pagoVacaciones, 2);
        $total += round($pagoVacaciones, 2);
        $completa['prima_vacacional'] = round($pagoVacaciones * 0.25, 2);
        $total += round($pagoVacaciones * 0.25, 2);
        $completa['prima_antiguedad'] = round($salarioTopado * $anios_antiguedad * 12, 2);
        $total += round($salarioTopado * $anios_antiguedad * 12, 2);
        $gratificacionB = ($anios_antiguedad_int * 20) * $remuneracionDiaria;
        $completa['gratificacion_b'] = round($gratificacionB);
        $completa['total'] = round($total, 2);
        $resultado['completa'] = $completa;
        $resultado['anios_antiguedad'] = $anios_antiguedad_int;

        $porcentajes = [90, 80, 70, 60, 50];
        $cien_porciento = 90;

        foreach ($porcentajes as $porcentaje) {
            $propuesta = $cien_porciento * ($porcentaje / 100);
            $total = 0;
            $alPorcentaje = [];
            $alPorcentaje['indemnizacion'] = round(($remuneracionDiaria * (1 + (15 / 365) + ($diasVacaciones * .25 / 365))) * $propuesta, 2);
            $total += round(($remuneracionDiaria * (1 + (15 / 365) + ($diasVacaciones * .25 / 365))) * $propuesta, 2);
            $alPorcentaje['aguinaldo'] = round($remuneracionDiaria * 15 * $propAguinaldo, 2);
            $total += round($remuneracionDiaria * 15 * $propAguinaldo, 2);
            $alPorcentaje['vacaciones'] = round($pagoVacaciones, 2);
            $total += round($pagoVacaciones, 2);
            $alPorcentaje['prima_vacacional'] = round($pagoVacaciones * 0.25, 2);
            $total += round($pagoVacaciones * 0.25, 2);

            if ($anios_antiguedad >= 15) {
                $alPorcentaje['prima_antiguedad'] = round($salarioTopado * $anios_antiguedad * 12, 2);
                $total += round($salarioTopado * $anios_antiguedad * 12, 2);
            } else {
                $primaAntiguedadEscalada = $salarioTopado * $anios_antiguedad * 12 * ($porcentaje / 100);
                $alPorcentaje['prima_antiguedad'] = round($primaAntiguedadEscalada, 2);
                $total += round($primaAntiguedadEscalada, 2);
            }

            $alPorcentaje['total'] = round($total, 2);
            $resultado['al' . $porcentaje] = $alPorcentaje;
        }

        return $resultado;
    }
}
