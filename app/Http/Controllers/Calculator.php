<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Services\LiquidacionCalculator;

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
            
            $anios_antiguedad = Carbon::parse($request->fecha_ingreso)
                ->diffInDays(Carbon::parse($request->fecha_salida))
                / 365;
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
    
            $ano_vigencia = DB::table('calculadora_configuraciones')
                ->where('nombre', 'año de vigencia de vacaciones')
                ->value('valor');
            $ano_vigencia_entero = intval($ano_vigencia);

            if ($anioSalida >= $ano_vigencia_entero) {
                $datosL['vac'] = 'calculadora_dias_vacaciones_actual';
                $diasVacaciones = \DB::table('calculadora_dias_vacaciones_actual')->where('antiguedad', '>=', ceil($anios_antiguedad))->value('dias');
            } else {
                $datosL['vac'] = 'calculadora_dias_vacaciones_anterior';
                $diasVacaciones = \DB::table('calculadora_dias_vacaciones_anterior')->where('antiguedad', '>=', ceil($anios_antiguedad))->value('dias');
            }

            $inputs = [
                'anios_antiguedad' => $anios_antiguedad,
                'propVacaciones' => $propVacaciones,
                'remuneracionDiaria' => $remuneracionDiaria,
                'salarioMinimo' => $salarioMinimo,
                'propAguinaldo' => $propAguinaldo,
                'anios_antiguedad_int' => $anios_antiguedad_int,
                'diasVacaciones' => $diasVacaciones,
            ];

            $datosL = array_merge($datosL, (new LiquidacionCalculator())->calcular($inputs));
            
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
