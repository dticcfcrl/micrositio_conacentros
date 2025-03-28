<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\AuthHelper;
use App\Models\CitasDisponibilidadOficinas;
use App\Models\CitasConfiguracionOficinas;
use App\Models\User;

class Dashboard extends Controller
{
    //
    /*
     * Dashboard Pages Routs
     */
    public function index(Request $request)
    {
        $auth_user = AuthHelper::authSession();
        /* dd($auth_user); */
        $assets = ['chart', 'animation'];

        $fechasUTC = $this->obtenerHorarioUTC($auth_user->id_ccls);

        $fechaTiempoActualUTC = $fechasUTC[0];
        $fechaActual = $fechasUTC[1];
        $tiempoActual = $fechasUTC[2];

        // Se definen variables para compensar optimizar consultas por perfil
        $disponible = null; 
        $fechasDisponibles = null;
        $disponbilidadOficina = null;
        $conciliadores = null;
        $auxiliares = null;
        $citasAtendidas = 0;
        $citasPendientes = 0;
        $citasCanceladas = 0;
        $citasAtendidasSemana = 0;
        $citasPendientesSemana = 0;
        $citasCanceladasSemana = 0;
        $totalOficinas = 0; 
        $listasCitas = 0; 
        $usuariosAdmin = 0; 
        $usuariosCs = 0; 
        $usuariosAux = 0;

        $estados = \DB::table('estados as t1')
        ->select('t1.clave', 't1.nombre')
        ->where('t1.clave', $auth_user->id_estado)
        ->get();

        // Datos de ubicación oficina
        $cclsUbicaciones = \DB::table('ccls as t1')
        ->select('t1.id', 't1.estado','t1.municipio', 't1.ambito','t1.direccion', 
        't1.url_google', 't1.lat','t1.long', 't1.contacto', 't1.cp')
        ->where('t1.id', $auth_user->id_ccls)->get(); 

        if($auth_user->perfil === 'superusuario'){

            // Obtenemos el total de las oficinas
            $totalOficinas = DB::table('ccls')
            ->count();

            // Obtenemos las oficinas configuradas listas para agendar citas
            $listasCitas = DB::table('ccls as t1')
            ->join('citas_configuracion_oficinas as t2', 't1.id', '=', 't2.id_ccls')
            ->join('users as t3', 't1.id', '=', 't3.id_ccls')
            ->where('t3.perfil', '=', 'administrador')
            ->where('t3.status', '=', '1')
            ->count();

            // Obtenemos administadores
            $usuariosAdmin = User::where('perfil', 'administrador')->count();
            $usuariosCs = User::where('perfil', 'conciliador')->count();
            $usuariosAux = User::where('perfil', 'Auxiliar')->count();

            /* dd($totalOficinas, $listasCitas, $usuariosAdmin, $usuariosCs, $usuariosAux); */
        }

        if($auth_user->perfil === 'administrador'){

            // Obtenemos la disponibilidad por horarios
            $disponible = CitasDisponibilidadOficinas::where('id_administrador',  $auth_user->id)
            ->orderBy('horario')
            ->get();
            
            // Obtenemos las fechas disponibles por oficina
            $fechasDisponibles = DB::table('citas_disponibilidad_fechas_oficinas as t1')
            ->where('t1.id_ccls', $auth_user->id_ccls)
            ->count();

            // Obtenemos la configuración de días
            $disponbilidadOficina = CitasConfiguracionOficinas::where('id_administrador', $auth_user->id)->first();

            // Obtenemos el número de conciliadores registrados por oficina
            $conciliadores = DB::table('users')
            ->select(DB::raw('count(id) as conciliadores'))
            ->where('id_ccls', $auth_user->id_ccls)
            ->where('perfil', 'conciliador')
            ->groupBy('id')
            ->count();
            
            // Obtenemos el número de auxiliares registrados por oficina
            $auxiliares = DB::table('users')
            ->select(DB::raw('count(id) as auxiliares'))
            ->where('id_ccls', $auth_user->id_ccls)
            ->where('perfil', 'auxiliar')
            ->groupBy('id')
            ->count();

        }

        if($auth_user->perfil === 'conciliador'){

            // Obtenemos las citas atendidas
            $citasAtendidas = DB::table('citas_registradas as t1')
            ->select('t1.status_conciliador')
            ->where('t1.id_conciliador', $auth_user->id)
            ->where('t1.cita_fecha', '=', $fechaActual)
            ->where('t1.status_conciliador', '=', 1)
            ->Where('t1.status', '=', 1)
            ->count();

            // Obtenemos las citas pendientes
            $citasPendientes = DB::table('citas_registradas as t1')
            ->select('t1.status_conciliador')
            ->where('t1.id_conciliador', $auth_user->id)
            ->where('t1.cita_fecha', '=', $fechaActual)
            ->where('t1.status_conciliador', '=', 2)
            ->Where('t1.status', '=', 1)
            ->count();
            
            // Obtenemos las citas canceladas
            $citasCanceladas = DB::table('citas_registradas as t1')
            ->select('t1.status_conciliador')
            ->where('t1.id_conciliador', $auth_user->id)
            ->where('t1.cita_fecha', '=', $fechaActual)
            ->where(function($query) {
                $query->where('t1.status_conciliador', '=', 0)
                      ->orWhere('t1.status', '=', 0);
            })
            ->count();

            // Obtenemos las citas atendidas por semana
            $citasAtendidasSemana = DB::table('citas_registradas as t1')
            ->select('t1.status_conciliador')
            ->where('t1.id_conciliador', $auth_user->id)
            ->where(DB::raw("week('t1.cita_fecha') = week(current_date())"))
            ->where('t1.status_conciliador', '=', 1)
            ->Where('t1.status', '=', 1)
            ->count();
            
            // Obtenemos las citas pendientes por semana
            $citasPendientesSemana = DB::table('citas_registradas as t1')
            ->select('t1.status_conciliador')
            ->where('t1.id_conciliador', $auth_user->id)
            ->where(DB::raw("week('t1.cita_fecha') = week(current_date())"))
            ->where('t1.status_conciliador', '=', 2)
            ->Where('t1.status', '=', 1)
            ->count();
            
            // Obtenemos las citas canceladas por semana
            $citasCanceladasSemana = DB::table('citas_registradas as t1')
            ->select('t1.status_conciliador')
            ->where('t1.id_conciliador', $auth_user->id)
            ->where(DB::raw("week('t1.cita_fecha') = week(current_date())"))
            ->where(function($query) {
                $query->where('t1.status_conciliador', '=', 0)
                      ->orWhere('t1.status', '=', 0);
            })
            ->count();

        }        
        
        return view('dashboards.dashboard', compact('assets', 'estados', 'cclsUbicaciones', 'auth_user', 
        'disponible', 'fechasDisponibles' ,'citasAtendidas', 'citasPendientes', 'citasCanceladas', 
        'disponbilidadOficina', 'conciliadores', 'auxiliares', 'citasAtendidasSemana', 
        'citasPendientesSemana', 'citasCanceladasSemana', 'totalOficinas', 'listasCitas',
        'usuariosAdmin', 'usuariosCs', 'usuariosAux'));
    }

    /*Función de apoyo para obtener los horarios utc por oficina */
    private function obtenerHorarioUTC($id_ccls){

        // Obtenemos el horario de oficina
        /*Obtenemos la entidad, municipio y dirección de la oficina */
        $oficinaUTC = DB::table('ccls as t1')->select('t1.zona_horaria')
        ->where('t1.id', $id_ccls)->get()->first();        

        // se restan horas por servidor configurado en UTC 00:00 de acuerdo a la configuración guardad de oficina
        if($oficinaUTC->zona_horaria === null){
            $utcCDMX = '-6 hour'; 
        }else{
            $utcCDMX = substr($oficinaUTC->zona_horaria,-3, 2).' hour';
        }
        
        // - NOTA: Este valor ($utcCDMX) debería de ser tomado de datos de oficina

        $fechaTiempoActualUTC = date('Y-m-d H:i:s', strtotime($utcCDMX)); // fecha y hora actual configuración UTC
        $fechaActual = date('Y-m-d', strtotime($fechaTiempoActualUTC)); // fecha actual 
        $tiempoActual = date('H:i:s', strtotime($fechaTiempoActualUTC)); // hora actual

        $fechaUTC = [$fechaTiempoActualUTC, $fechaActual, $tiempoActual];

        return $fechaUTC;
    }
}

