<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class Calculator extends Controller
{
    public function resultadoCalculoPrestaciones(Request $request)
    {
        $result = $this->getLaboralesConceptosPre($request);

        return $result;
    }

    public function moneyFormat($amount)
    {
        return '$' . number_format($amount, 2);
    }

    public function getLaboralesConceptosPre(Request $request) {
        try {
            $diasPeriodicidad =  \DB::table('calculadora_periodicidad')->where('id', $request->periodicidad_id)->value('dias');
            $remuneracionDiaria = floatval($request->remuneracion) / $diasPeriodicidad;
            $fechaSalida = Carbon::now();
            $zona = $request->zona;
            $zona_search = $zona == 1 ? "frontera norte" : "general";
            $zona_fronteriza = $zona == 1 ? "Sí" : "No";
            
            if ($request->fecha_salida != '' && $request->fecha_salida != null) {
                $fechaSalida = Carbon::parse($request->fecha_salida)->addHours(24);
            }
            
            $anios_antiguedad = Carbon::parse($request->fecha_ingreso)->floatDiffInYears($fechaSalida);
            $anioSalida = Carbon::parse($request->fecha_salida)->format('Y');
            $anios_antiguedad_int = intval($anios_antiguedad);
    
            if ($anios_antiguedad == floor($anios_antiguedad)) {
                $propVacaciones = 1;
            } else {
                $propVacaciones = $anios_antiguedad - floor($anios_antiguedad);
            }
    
            $profesion = "-";
            if ($request->ocupacion_id != '' && $request->ocupacion_id != null) {
                $salarioMinimo = \DB::table('calculadora_salarios')->where([['id_profesion', $request->ocupacion_id], ['anio', $anioSalida], ['zona', $zona_search]])->value('salario');

                $profesion = \DB::table('calculadora_profesiones')->where([['id', $request->ocupacion_id]])->value('profesion');

                if($salarioMinimo == -1 || $salarioMinimo == null) {
                    $salarioMinimo = \DB::table('calculadora_salarios')->where([['id_profesion', 1], ['anio', $anioSalida], ['zona', $zona_search]])->value('salario');
                }
            } else {
                $salarioMinimo = \DB::table('calculadora_salarios')->where([['id_profesion', 1], ['anio', $anioSalida], ['zona', $zona_search]])->value('salario');
            }
    
            $datosL = [];
            $datosL['remuneracionDiaria'] = $this->moneyFormat($remuneracionDiaria);
            $datosL['antiguedad'] = $anios_antiguedad;
            $datosL['antiguedadInt'] = $anios_antiguedad;
            $datosL['salarioMinimo'] = $this->moneyFormat($salarioMinimo);
            $datosL['zona_fronteriza'] = $zona_fronteriza;
            $datosL['fechaInicio'] =  Carbon::parse($request->fecha_ingreso)->format('Y-m-d');
            $datosL['fechaSalida'] = Carbon::parse($request->fecha_salida)->format('Y-m-d');
            $datosL['profesion'] = $profesion;
    
            $propAguinaldo = $this->calculoProporcionAguinaldo($request->fecha_ingreso, Carbon::parse($request->fecha_salida)->format('Y-m-d'));
    
            $params = [];
            $params['anios_antiguedad'] = $anios_antiguedad;
            $params['propVacaciones'] = $propVacaciones;
            $params['remuneracionDiaria'] = $remuneracionDiaria;
            $params['salarioMinimo'] = $salarioMinimo;
            $params['propAguinaldo'] = $propAguinaldo;
            $params['anios_antiguedad_int'] = $anios_antiguedad_int;
            $params['anioSalida'] = $anioSalida;
            $datosL = $this->calcularPropuestaDatosLaborales($datosL, $params);
            
            foreach($datosL["completa"] as &$value) {
                $value = $this->moneyFormat($value);
            }

            $porcentajes = [90, 80, 70, 60, 50];
            foreach($porcentajes as $porcentaje) {
                foreach($datosL["al".$porcentaje] as &$value) {
                    $value = $this->moneyFormat($value);
                }
            }

            return $datosL;
        }
        catch(\Throwable $e) {
            $datosL = [];
            $datosL['error'] = true;
            $datosL['mensaje'] = 'No se encontraron datos' . $e;
            
            return $datosL;
        }
    }
    
    private function calcularPropuestaDatosLaborales($datosL, $data) {
            $anios_antiguedad = $data['anios_antiguedad'];
            $propVacaciones = $data['propVacaciones'];
            $remuneracionDiaria = $data['remuneracionDiaria'];
            $salarioMinimo = $data['salarioMinimo'];
            $propAguinaldo = $data['propAguinaldo'];
            $anios_antiguedad_int = $data['anios_antiguedad_int'];
            $anioSalida = $data['anioSalida'];
            
            $ano_vigencia = DB::table('calculadora_configuraciones')
            ->where('nombre', 'año de vigencia de vacaciones')
            ->value('valor');
    
            $ano_vigencia_entero = intval($ano_vigencia);

            if($anioSalida >= $ano_vigencia_entero){
                $datosL['vac'] = 'calculadora_dias_vacaciones_actual';
                $diasVacaciones = \DB::table('calculadora_dias_vacaciones_actual')->where('antiguedad', '>=', ceil($anios_antiguedad))->value('dias');
            } else {
                $diasVacaciones = \DB::table('calculadora_dias_vacaciones_anterior')->where('antiguedad', '>=', ceil($anios_antiguedad))->value('dias');
                $datosL['vac'] = 'calculadora_dias_vacaciones_anterior';
            }
    
            $pagoVacaciones = $propVacaciones * $diasVacaciones * $remuneracionDiaria;
            $salarioTopado = ($remuneracionDiaria > (2 * $salarioMinimo) ? (2 * $salarioMinimo) : $remuneracionDiaria);
                
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
            $datosL['completa'] = $completa;
            $datosL['anios_antiguedad'] = $anios_antiguedad_int;
            
            $porcentajes = [90, 80, 70, 60, 50];
            $cien_porciento = 90;

            foreach ($porcentajes as $porcentaje) {
                $propuesta = $cien_porciento * ($porcentaje / 100);
                $total = 0;
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
                $datosL['al'.$porcentaje] = $alPorcentaje;
            }
            return $datosL;
    }
    
    public function calculoProporcionAguinaldo($fecha_ingreso, $fecha_salida) {
            $fechaSalida = Carbon::parse($fecha_salida);
            $fechaIngreso = Carbon::parse($fecha_ingreso);
    
            $salidaInicioAnio = Carbon::parse($fecha_salida)->startOfYear();
            $dias_trabajados = $salidaInicioAnio->diffInDays($fechaSalida->startOfDay()) + 1;
            $propAguinaldo = $dias_trabajados / 365;

            if ($fechaIngreso->gt($salidaInicioAnio)) {
                $dias_trabajados = $fechaSalida->diffInDays($fechaIngreso->startOfDay());
                $propAguinaldo = $dias_trabajados / 365; 
            }

            return $propAguinaldo;
    }

    public function obtenerPDF(Request $request) {
        $datos = $this->getLaboralesConceptosPre($request);
        
        return view('partials.functionality.calculator.pdf', compact('datos'));
    }

}
