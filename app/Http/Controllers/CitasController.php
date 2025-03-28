<?php

namespace App\Http\Controllers;

use App\Models\CitasRegistrada;
use App\Models\User;
use App\Models\CitasDisponibilidadOficinas;
use App\Models\CitasConfiguracionOficinas;
use App\Models\CitasDisponibilidadFechasOficinas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Helpers\AuthHelper;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Mail;
use App\Mail\NotificacionCCLS;
use App\Mail\NotificacionCancelarCCLS;
use App\Mail\NotificacionCancelarFechasCCLS;
use App\Mail\NotificacionCitaAccionCCLS;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Http\Post;
use App\Http\Controllers\JoinClause;
use Exception;
use Illuminate\Database\Query\JoinClause as QueryJoinClause;
use Illuminate\Support\Facades\Date;
use IntlChar;
use Statamic\Entries\Entry;
use Statamic\Facades\Link;
use Illuminate\Support\Facades\Log;

class CitasController extends Controller
{

    //---------------------- CONTROLADOR CITAS PARA USUARIOS ----------------------//
    //---------------------------------- INICIO -----------------------------------//

    /* Vista de para Agendar Cita */
    public function agendarCita(Request $request)
    {        

        // Obtemos la cita con el folio ingresado para realizar el cambio de cita
        $cita = DB::table('citas_registradas as t1')
        ->join('estados as t2', 't2.clave', '=', 't1.id_estado')
        ->where('t1.cita_folio', $request->folio)
        ->where('t1.status', 1)
        ->where('t1.status_conciliador', '=', '2')
        ->get()
        ->first();

        $estados = DB::table('estados as t1')->select('t1.clave', 't1.nombre')->get();         
        
        return view('public.citas.agendar-cita', compact("estados", "request", "cita"));
    }

    /* Obtener oficina por Estados */
    public function agendarCitaCcls($entidadSeleccionada)
    {
        $ccls = DB::table('ccls as t1')->select('t1.id', 't1.ambito', 't1.municipio', 't1.direccion', 't1.liga_cita_local')
                ->where('t1.estado', '=', $entidadSeleccionada)
                ->where('status', 1)->get();

        return $ccls;
    }

    /*Obtenemos las fechas por oficinas*/
    public function disponibilidadFechasCcls($oficinaSelecionada)
    {

        $fechasUTC = $this->obtenerHorarioUTC($oficinaSelecionada);

        $fechaTiempoActualUTC = $fechasUTC[0];
        $fechaActual = $fechasUTC[1];
        $tiempoActual = $fechasUTC[2];

        $disponibilidadFechas = collect([]); // arreglo de objetos
        $i = 0; // variable auxiliar

        /*Obtenemos el número de conciliadores */
        $conciliadores = User::where('id_ccls', $oficinaSelecionada)
        ->where('perfil', 'conciliador')
        ->where('status', '1')
        ->count();
        
        /*Obtenemos las fechas por oficina*/
        $ccls = DB::table('citas_disponibilidad_fechas_oficinas')
        ->select('id_ccls', 'fecha', 'status')
        ->where('id_ccls', $oficinaSelecionada)
        ->where('fecha', '>=', $fechaActual)
        ->get();

        /*Obtenemos las vacaciones*/
        // Subconsulta interna
        $subquery = DB::table('citas_configurar_vacaciones as t1')
        ->join('users as t2', 't1.id_usuario', '=', 't2.id')
        ->select('t1.*', 't2.perfil')
        ->where('t2.status', '=', '1')
        ->where('t2.perfil', 'conciliador');

        // Subconsulta externa
        $innerQuery = DB::table(DB::raw("({$subquery->toSql()}) as usuarios"))
        ->mergeBindings($subquery) // Agrega los bindings de la subconsulta
        ->join('users as t2', 'usuarios.id_responsable', '=', 't2.id')
        ->select('usuarios.id_responsable', 'usuarios.fecha', 't2.id_ccls')
        ->where('usuarios.status', 1)
        ->where('t2.id_ccls', $oficinaSelecionada);

        // Consulta principal
        $vacaciones = DB::table(DB::raw("({$innerQuery->toSql()}) as X"))
        ->mergeBindings($innerQuery) // Agrega los bindings de la subconsulta
        ->select('X.id_responsable', 'X.fecha', 'X.id_ccls', DB::raw('COUNT(X.fecha) as usuarios'))
        ->groupBy('X.id_responsable', 'X.fecha', 'X.id_ccls') // Agrupamos por todas las columnas no agregadas
        ->get()->toArray();

        /*Obtenemos citas por fecha Y oficina*/
        $citas = DB::table('citas_registradas')
        ->select(DB::raw('cita_fecha, count(cita_fecha) as citas'))
        ->where('id_ccls', $oficinaSelecionada)
        ->where('status', 1)
        ->where('status_conciliador', '=', '2')
        ->where('cita_fecha', '>=', $fechaActual)
        ->groupBy('cita_fecha')
        ->get()->toArray();

        /*Obtenemos el total las fechas y horas por citas*/
        $horasconfig = DB::table('citas_disponibilidad_oficinas')
        ->select(DB::raw('aplica, count(aplica) as horas'))
        ->where('id_ccls', $oficinaSelecionada)
        ->where('status', 1)
        ->groupBy('aplica')
        ->orderBy('aplica', 'desc')
        ->get();       

        /*Obtenemos la configuración de oficina*/
        $cclConfig = DB::table('citas_configuracion_oficinas')
        ->select('status_lunes', 'status_martes', 'status_miercoles', 'status_jueves', 'status_viernes', 'meses_cita', 'aplica', 'id')
        ->where('id_ccls', $oficinaSelecionada)
        ->where('status', 1)
        ->orderBy('aplica', 'desc')
        ->get();

        // obtenemos la fecha máxima configurada activa
        $maxAplica = CitasConfiguracionOficinas::where('id_ccls', $oficinaSelecionada)
            ->selectRaw('id, MAX(aplica) as aplica')
            ->where('status', 1)
            ->groupBy('id')
            ->orderBy(DB::raw('MAX(aplica)'), 'desc')
            ->get();

        $diccionario_citas = [];
        foreach ($citas as $cita) {
            $diccionario_citas[$cita->cita_fecha] = $cita->citas;
        }

        $diccionario_vacaciones = [];
        foreach ($vacaciones as $vacacion) {
            $diccionario_vacaciones[$vacacion->fecha] = $vacacion->usuarios;
        }
        
        if(isset($cclConfig) && count($cclConfig) > 0){ 

            /*Iteramos las fechas de oficina disponibles y agregamos las fechas de citas registradas*/
            foreach ($ccls as $ccl) {
                if(count($cclConfig) > 1){

                    if($ccl->fecha < $maxAplica[0]->aplica){
                        // fecha menor
                        $i = 1;
                        $horas = $horasconfig[$i]->horas;
                    }
    
                    if($ccl->fecha >= $maxAplica[0]->aplica){
                        // fecha mayor o igual
                        $i = 0;
                        $horas = $horasconfig[$i]->horas;
                    }
                } else {
                    $i = 0;
                    $horas = $horasconfig[0]->horas;
                }

                $dia = $this->obtenerDiaSemana($ccl->fecha);

                /*Otenemos el día de semana por fecha*/
                switch($dia){
                    case 'lunes':
                        $fecAplica = $cclConfig[$i]->aplica;
                        if($cclConfig[$i]->status_lunes){
                            $status_dia = true;
                        }else{
                            $status_dia = false;
                        }
                    break;
                    case 'martes':
                        $fecAplica = $cclConfig[$i]->aplica;
                        if($cclConfig[$i]->status_martes){
                            $status_dia = true;
                        }else{
                            $status_dia = false;
                        }
                    break;
                    case 'miércoles':
                        $fecAplica = $cclConfig[$i]->aplica;
                        if($cclConfig[$i]->status_miercoles){
                            $status_dia = true;
                        }else{
                            $status_dia = false;
                        }
                    break;
                    case 'jueves':
                        $fecAplica = $cclConfig[$i]->aplica;
                        if($cclConfig[$i]->status_jueves){
                            $status_dia = true;
                        }else{
                            $status_dia = false;
                        }
                    break;
                    case 'viernes':
                        $fecAplica = $cclConfig[$i]->aplica;
                        if($cclConfig[$i]->status_viernes){
                            $status_dia = true;
                        }else{
                            $status_dia = false;
                        }
                    break;
                }

                $citas_agendadas = $diccionario_citas[$ccl->fecha] ?? 0;
                $usuarios_vacaciones = $diccionario_vacaciones[$ccl->fecha] ?? 0;
                $conciliadoresDisponibles = $conciliadores - $usuarios_vacaciones;

                $total_citas_disponibles = ($horas*$conciliadoresDisponibles) - $citas_agendadas;
                $total_citas_disponibles = $total_citas_disponibles > 0 ? $total_citas_disponibles : 0; // Si es negativo devuelve 0

                $disponibilidad = $conciliadoresDisponibles > 0 ?
                                    floor(($total_citas_disponibles * 100) / ($horas * $conciliadoresDisponibles)) : 0;

                /*Guardamos las fechas y calculamos el % de disponibilidad*/
                $disponibilidadFechas->push([
                    'fecha'=>$ccl->fecha,
                    'status_fecha'=>$ccl->status,
                    'aplica'=>$fecAplica,
                    'dia'=>$dia,
                    'status_dia'=>$status_dia,
                    'citas'=>$citas_agendadas,
                    'conciliadores'=>$conciliadoresDisponibles,
                    'porDisponibilidad'=>$disponibilidad
                ]);

            }

         }

        //En caso de no encontrar oficina configurada, al usuario le mostramos un mes, meramente por proceso
        if(isset($cclConfig->meses_cita)){
            $mostrar_meses = $cclConfig->meses_cita;
        }else{
            $mostrar_meses = 1;
        }
        
        return [$disponibilidadFechas, $mostrar_meses];
    }

    /* Obtenemos horarios disponibles  */
    public function disponibilidadCitaCcls($validarDisponibilidadOficina, $validarDisponibilidadFecha)
    {
        /*Obtenemos el día seleccionado por fullCaledar por conciliadores*/

        // variables auxiliares
        $statusDia = "t4.status_miercoles";

        $fechaCriterio = '>=';

        /*Obtenems el día de la semana */
        $diaSemana = $this->obtenerDiaSemana($validarDisponibilidadFecha);

        if($diaSemana != 'miércoles'){
            $statusDia = "t4.status_$diaSemana";
        }

        //Obtenemos el número de configuraciones activo. 1 o 2
        $numConfig = CitasConfiguracionOficinas::where('id_ccls', $validarDisponibilidadOficina)
        ->where('status', 1)
        ->get();

        /* Obtenemos los conciliadores que tienen ya citas en la fecha seleccionada*/
        $disponibilidad = DB::table('users as t1')
        ->select('t1.id', 't1.id_ccls', 't2.cita_hora')        
        ->join('citas_registradas as t2', 't2.id_conciliador', '=', 't1.id')
        ->where('t1.id_ccls', '=', $validarDisponibilidadOficina)
        ->where('t1.perfil', '=', 'conciliador')
        ->where('t1.status', '=', '1')        
        ->where('t2.status', '=', true)
        ->where('t2.status_conciliador', '<>', '0')
        ->where('t2.status_conciliador', '!=', false)
        ->where('t2.cita_fecha', '=', $validarDisponibilidadFecha)
        ->get();

        // obtenemos la fecha máxima configurada activa
        $maxAplica = CitasConfiguracionOficinas::where('id_ccls', $validarDisponibilidadOficina)
            ->selectRaw('id, MAX(aplica) as aplica')
            ->where('status', 1)
            ->groupBy('id')
            ->orderBy(DB::raw('MAX(aplica)'), 'desc')
            ->get();

        // Si oficina tiene dos configuracion
        if(count($numConfig) > 1){

            // caso para fecha menor a la máxima
            if($validarDisponibilidadFecha < $maxAplica[0]->aplica){
                $fechaCriterio = '<';
            }

            // caso para fecha mayor o igual a la máxima
            if($validarDisponibilidadFecha >= $maxAplica[0]->aplica){
                $fechaCriterio = '>=';
            }
        
        }

        if(count($maxAplica) > 0){

             /*Obtenemos los conciliadores unicos por oficinas estados y sus horarios y fechas disponibles*/
             $oficinas = DB::table('users as t1')
             ->select('t1.id', 't2.horario', 't4.aplica')
             ->join('citas_disponibilidad_oficinas as t2', 't2.id_ccls', '=', 't1.id_ccls')
             ->join('citas_disponibilidad_fechas_oficinas as t3', 't3.id_ccls', '=', 't1.id_ccls')
             ->join('citas_configuracion_oficinas as t4', 't4.id_ccls', '=', 't3.id_ccls')
             ->leftJoin('citas_configurar_vacaciones as t5', function($join) use ($validarDisponibilidadFecha) {
                 $join->on('t5.id_usuario', '=', 't1.id')
                      ->where('t5.fecha', '=', $validarDisponibilidadFecha)
                      ->where('t5.status', '=', 1);
             })
             ->where('t1.id_ccls', '=', $validarDisponibilidadOficina)
             ->where('t1.perfil', '=', 'conciliador')
             ->where('t1.status', '=', '1')
             ->where('t2.status', '=', 1)
             ->where('t3.status', '=', 1)
             ->where('t4.status', '=', 1)
             ->where('t3.fecha', '=', $validarDisponibilidadFecha)
             ->where($statusDia, '=', 1)
             ->where('t2.aplica', $fechaCriterio, $maxAplica[0]->aplica)
             ->where('t4.aplica', $fechaCriterio, $maxAplica[0]->aplica)
             ->whereNull('t5.id_usuario')  // Asegura que no haya un registro de vacaciones en esa fecha
            ->get();
            

            // Convertir conciliadores disponibles a un array de IDs
            $conciliadoresDisponibles = $oficinas->pluck('id')->toArray();
        
            // Filtramos conciliadores con citas agendadas que no están disponibles
            $conciliadoresNoDisponibles = $disponibilidad->filter(function ($cita) use ($conciliadoresDisponibles) {
                return !in_array($cita->id, $conciliadoresDisponibles);  // Si el conciliador no está disponible, lo marcamos
            });
        
            // Reasignar citas de conciliadores no disponibles a conciliadores disponibles
            if ($conciliadoresNoDisponibles->count() > 0) {
                $modified = False;
                foreach ($conciliadoresNoDisponibles as $cita) {
                    // Verificar si hay un conciliador disponible para esa misma hora
                    $conciliadorDisponible = DB::table('users as t1')
                        ->select('t1.id')
                        ->join('citas_disponibilidad_oficinas as t2', 't2.id_ccls', '=', 't1.id_ccls')
                        ->where('t1.id_ccls', '=', $validarDisponibilidadOficina)
                        ->where('t1.perfil', '=', 'conciliador')
                        ->where('t1.status', '=', '1')
                        ->where('t2.status', '=', 1)
                        ->where('t2.horario', '=', $cita->cita_hora) // Verificar conciliadores con la misma hora disponible
                        ->whereIn('t1.id', $conciliadoresDisponibles)  // Conciliadores disponibles
                        ->first();
        
                    if ($conciliadorDisponible) {
                        // Reasignar la cita al conciliador disponible
                        DB::table('citas_registradas')
                            ->where('id_conciliador', $cita->id)
                            ->where('cita_fecha', $validarDisponibilidadFecha)
                            ->where('cita_hora', $cita->cita_hora)
                            ->where('id_ccls', $cita->id_ccls)
                            ->update(['id_conciliador' => $conciliadorDisponible->id]);
        
                        $modified = True;
                    }
                }

                if($modified) {
                    // Actualizar la lista de disponibilidad con las nuevas asignaciones
                    $disponibilidad = DB::table('users as t1')
                    ->select('t1.id', 't1.id_ccls', 't2.cita_hora', 't2.id as id_cita')
                    ->join('citas_registradas as t2', 't2.id_conciliador', '=', 't1.id')
                    ->where('t1.id_ccls', '=', $validarDisponibilidadOficina)
                    ->where('t1.perfil', '=', 'conciliador')
                    ->where('t1.status', '=', '1')        
                    ->where('t2.status', '=', true)
                    ->where('t2.status_conciliador', '<>', '0')
                    ->where('t2.status_conciliador', '!=', false)
                    ->where('t2.cita_fecha', '=', $validarDisponibilidadFecha)
                    ->get();
                }
            }

            // Enviamos información de conciliadores con citas en fecha seleccionada
            // Eviamoss información de conciliadores por oficina disponible
            // Enviamos el ID de la configuración
        
            return [$disponibilidad, $oficinas, $maxAplica[0]->id];
        }

        
        return [$disponibilidad, [], 0];

    }

    /* Envío datos a DB */
    public function guardarCitaRegistrada(Request $request)
    {

        $auth_user = AuthHelper::authSession();

        //Obtenemos la hora UTC de la oficina
        $fechasUTC = $this->obtenerHorarioUTC($request->cclOficinas);

        $fechaTiempoActualUTC = $fechasUTC[0];
        $fechaActual = $fechasUTC[1];
        $tiempoActual = $fechasUTC[2];

        /*Validamos los campos*/
        $request->validate([
            'cclEstados'=>'required',
            'cclOficinas'=>'required',
            'cclFecha'=>'required',
            'cclHora'=>'required',
            'cclNombre'=>'required|max:50',
            'cclCorreo'=>'required|max:50',
            'cclCelular'=>'max:10',
            'cclApellidos'=>'required|max:50'
        ]);

        $hoy = Carbon::now()->format('Y-m-d');
        /* Validamos que exista solo una cita por correo activa */
        $validarCorreo = CitasRegistrada::where('status', 1)
                                        ->where('status_conciliador', '=', '2')
                                        ->where('correo', Str::lower(request('cclCorreo')))
                                        ->where("cita_fecha", '>=', $hoy)
                                        ->get();

        //Si no hay citas activas, procedemos a agendar cita
        if(count($validarCorreo) === 0){

            /*Obtenemos la clave y nombre de la entidad*/
            $estado = DB::table('estados as t1')->select('t1.clave', 't1.nombre')->where('t1.nombre', '=', request('cclEstados'))->get(); 
            
            /*Obtenemos la clave del estado*/
            $clave = $estado[0]->clave;

            /*Obtenemos el total de las citas por entidad, oficina, fecha y horas registradas*/
            $ConfirmarCita = CitasRegistrada::where('id_estado', $clave)
                                            ->where('id_ccls', request('cclOficinas'))
                                            ->where('cita_fecha', request('cclFecha'))
                                            ->where('cita_hora', request('cclHora'))
                                            ->where('status', 1)
                                            ->where('status_conciliador', '=', '2')
                                            ->count();

            /*Obtenemos el dia de la semana*/
            $diaSemana = $this->obtenerDiaSemana(request('cclFecha'));

            /*Obtenemos a los usuario conciliadores que atienden el en día y horario seleccionado*/
            $conciliadoresEstadoOficina = DB::table('users as t1')                
                ->join('citas_disponibilidad_oficinas as t2', 't2.id_ccls', '=', 't1.id_ccls')
                ->where('t1.id_estado', $clave) 
                ->where('t1.id_ccls', request('cclOficinas'))
                ->where('t1.perfil', 'conciliador')
                ->where('t2.horario', request('cclHora'))
                ->count();
                
            /*Verificamos que las citas regitradas sean menor o igual a los conciliadores disponibles*/
            if($ConfirmarCita != $conciliadoresEstadoOficina ){
                
                //Generamos el folio
                $folio = $this->obtenerFolio($request->cclNombre, $request->cclApellidos, $request->cclFecha, $request->cclHora, $tiempoActual);

                /*Obtenemos la entidad, municipio y dirección de la oficina */
                $oficina = DB::table('ccls as t1')->select('t1.id','t1.estado','t1.municipio', 't1.direccion', 't1.url_google', 't1.lat', 't1.long')->where('t1.id', '=', $request->cclOficinas)->get();            
        
                /*Obtenemos el correo del administrador para enviar Copia oculta */
                /* $correoConciliador = User::where('id', request('cclConciliador'))->first(); */
                $correoCcl = User::where('id_ccls', request('cclOficinas'))
                ->where('perfil', '=', 'administrador')
                ->where('status', '=', '1')
                ->first();

                try{

                    //Comenzamos transacción
                    DB::beginTransaction();

                    CitasRegistrada::create([
                        'id_estado' => $clave,
                        'id_ccls' => request('cclOficinas'),
                        'correo' => Str::lower(request('cclCorreo')),
                        'celular' => request('cclCelular'),
                        'cita_fecha' => request('cclFecha'),
                        'cita_hora' => request('cclHora'),
                        'nombre' => Str::lower(request('cclNombre')),
                        'apellidos' => Str::lower(request('cclApellidos')),
                        'observaciones' => Str::lower(request('cclObservaciones')),
                        'status' => request('status'),
                        'id_conciliador' => request('cclConciliador'),
                        'cita_folio' => $folio,
                        'id_configuracion' => request('cclIDConfig'),
                        'created_at' => $fechaTiempoActualUTC,
                        'updated_at' => $fechaTiempoActualUTC
                    ]);

                    //Terminamos la transacción
                    DB::commit();

                     //Si logramos guardar en la DB y todo está bien, enviamos correo de cita registrada
                     try {
                        /*Enviamos el correo con los datos de la reserva*/
                        Mail::to(request('cclCorreo'))->bcc($correoCcl->buzon ? $correoCcl->buzon : $correoCcl->email)->send(new NotificacionCCLS($request, $folio, $oficina));
                    } catch(Exception $e) {
                        Mail::to($correoCcl->buzon ? $correoCcl->buzon : $correoCcl->email)->send(new NotificacionCCLS($request, $folio, $oficina, true));
                        /* $folio = request('cclFolio'); */

                        DB::table('citas_registradas')
                        ->where('cita_folio', $folio)
                        ->update(['correo_enviado' => '0', 'updated_at' => now()]);

                        $mensaje = 'No se pudo enviar al correo registrado. Asegúrese de guardar el folio para evitar perderlo o ingrese nuevamente su correo para enviarle la información.';
                        $error_codigo = 20;

                        return view('public.citas.redireccionar', compact('error_codigo', 'mensaje', 'folio'));
                    }

                }catch(\Exception $e){
                    /* En caso de error al intentar registrar cita, 
                    regresamos a la vista con un mensaje de error y revertimos los registros fallidos, 
                    en caso de haber */
                    DB::rollBack();

                    $folio = "";
                    $mensaje = 'No se pudo generar su cita, intente agendar una cita nuevamente por favor.';
                    $error_codigo = 11;
                    
                    return view('public.citas.redireccionar', compact('error_codigo', 'mensaje', 'folio'));
                }

                $folio = $folio;
                $mensaje = '';
                $error_codigo = 0;
                
                return view('public.citas.redireccionar', compact('error_codigo', 'mensaje', 'folio'));

            } else {
                $folio = "";
                $mensaje = 'La hora no se encuentra disponible, vuelva a intentarlo por favor.';
                $error_codigo = 12;
                
                return view('public.citas.redireccionar', compact('error_codigo', 'mensaje', 'folio'));
            } 
            
        } else {
            $folio = "";
            $mensaje = 'Ya cuenta con un folio. Si necesita una nueva cita de clic en el botón de "Consultar cita" para consultar su folio anterior, después de clic en "Cancelar cita" para agendar una nueva.';
            $error_codigo = 10;
            
            return view('public.citas.redireccionar', compact('error_codigo', 'mensaje', 'folio'));
        }
    }

    /* Reenviar correo */
    public function reenviarCorreo(Request $request){

        $folio = request('folio');
        $correo = Str::lower(request('correo'));
        $hoy = Carbon::now()->format('Y-m-d');
        $validarCorreo = CitasRegistrada::where('status', 1)
                                        ->where('status_conciliador', '=', '2')
                                        ->where('correo', $correo)
                                        ->where("cita_fecha", '>=', $hoy)
                                        ->get();
        //Si no hay ciitas activas con ese correo, procedemos a cambiar la cita
        if (count($validarCorreo) === 0) {
            // Obtenemos datos de folio a cancelar cita
            $enviarCorreo = DB::table('citas_registradas as t1')
            ->select('t1.cita_folio', 't1.correo', 't1.cita_fecha', 't1.cita_hora', 't1.nombre', 't1.apellidos', 't1.observaciones', 't1.id_ccls', 't1.id_estado', 't2.buzon', 't2.email', 't3.direccion')
            ->join('users as t2', 't1.id_ccls' , '=', 't2.id_ccls')
            ->join('ccls as t3', 't2.id_ccls', '=', 't3.id')
            ->where('t1.cita_folio', '=', $folio)
            ->where('t2.perfil', '=', 'administrador')
            ->where('t2.status', '=', '1')
            ->where('t1.status', '=', 1)
            ->where('t1.correo_enviado', '=', 0)
            ->where('t1.correo_reenviado', '=', 0)
            ->first();

            $mensaje = 'Correo enviado exitosamente';
            $error_codigo = 0;

            try{
                if($enviarCorreo) {
                    $status = 0;
                    $oficina = DB::table('ccls as t1')->select('t1.id','t1.estado','t1.municipio', 't1.direccion', 't1.url_google', 't1.lat', 't1.long')->where('t1.id', '=', $enviarCorreo->id_ccls)->get();
                    $cita = [
                        'tipoCita' => '0',
                        'cclFolio' => $enviarCorreo->cita_folio,
                        'cclEstados' => $enviarCorreo->id_estado,
                        'cclOficinas' => $enviarCorreo->id_ccls,
                        'cclFecha' => $enviarCorreo->cita_fecha,
                        'cclHora' => $enviarCorreo->cita_hora,
                        'cclNombre' => $enviarCorreo->nombre,
                        'cclCorreo' => $correo,
                        'cclApellidos' => $enviarCorreo->apellidos
                    ];

                    //Si logramos cancelar cita en la DB y todo está bien, enviamos correo de cita cancelada
                    try {
                        /*Enviamos el correo con los datos de la reserva*/
                        Mail::to($correo)->bcc($enviarCorreo->buzon ? $enviarCorreo->buzon : $enviarCorreo->email)->send(new NotificacionCCLS($cita, $oficina, false, $correo));
                        $status = 1;
                    } catch(Exception $e) {
                        $mensaje = 'No se pudo enviar al correo: "'.$correo.'". Asegúrese de guardar el folio para evitar perderlo.';
                        $error_codigo = 20;
                        $correo = $enviarCorreo->correo;
                    }
                    //Comenzamos transacción
                    DB::beginTransaction();
                                
                    // Marcamos correo como enviado
                    DB::table('citas_registradas')
                    ->where('cita_folio', $folio)
                    ->where('status', 1)
                    ->where('status_conciliador', '2')
                    ->update(['correo_enviado' => $status,'correo_reenviado' => '1', 'correo' => $correo, 'updated_at' => now()]);

                    //Terminamos la transacción
                    DB::commit();
                } else {
                    $mensaje = 'No se pudo procesar su solicitud.';
                    $error_codigo = 20;
                }

            } catch(Exception $e) {
                /* En caso de error al intentar cancelar cita, 
                regresamos a la vista con un mensaje de error y revertimos los registros fallidos, 
                en caso de haber */
                DB::rollBack();
                $mensaje = 'No se pudo procesar su solicitud. Intentelo nuevamente.';
                $error_codigo = 20;            
            }
        } else {
            DB::table('citas_registradas')
                    ->where('cita_folio', $folio)
                    ->where('status', 1)
                    ->where('status_conciliador', '2')
                    ->update(['correo_enviado' => 0,'correo_reenviado' => 1, 'updated_at' => now()]);

            $mensaje = 'No se pudo procesar su solicitud.';
            $error_codigo = 20;
        }

        return view('public.citas.redireccionar', compact('error_codigo', 'mensaje', 'folio'));;
    }

    /* Cancelar cita */
    public function citaCanceladaCcls($folio){
        
        // Obtenemos datos de folio a cancelar cita
        $cancelarCita = DB::table('citas_registradas as t1')
        ->select('t1.cita_folio', 't1.correo', 't1.cita_fecha', 't1.cita_hora', 't1.nombre', 't1.apellidos', 't1.observaciones', 't2.buzon', 't2.email', 't3.direccion')
        ->join('users as t2', 't1.id_ccls' , '=', 't2.id_ccls')
        ->join('ccls as t3', 't2.id_ccls', '=', 't3.id')
        ->where('t1.cita_folio', '=', $folio)
        ->where('t2.perfil', '=', 'administrador')
        ->where('t2.status', '=', '1')
        ->where('t1.status', '=', 1)
        ->first();

        try{
            if($cancelarCita) {
                //Comenzamos transacción
                DB::beginTransaction();

                            
                // Cancelamos cita
                DB::table('citas_registradas')
                ->where('cita_folio', $folio)
                ->where('status', 1)
                ->where('status_conciliador', '2')
                ->update(['status' => '0', 'updated_at' => now()]);

                //Terminamos la transacción
                DB::commit();

                //Si logramos cancelar cita en la DB y todo está bien, enviamos correo de cita cancelada
                try {
                    /*Enviamos el correo con los datos de la reserva*/
                    Mail::to($cancelarCita->correo)->bcc($cancelarCita->buzon ? $cancelarCita->buzon : $cancelarCita->email)->send(new NotificacionCancelarCCLS($cancelarCita));
                } catch(Exception $e) {
                    Mail::to($cancelarCita->buzon ? $cancelarCita->buzon : $cancelarCita->email)->send(new NotificacionCancelarCCLS($cancelarCita, true));
                }
            }


        }catch(Exception $e){
            /* En caso de error al intentar cancelar cita, 
            regresamos a la vista con un mensaje de error y revertimos los registros fallidos, 
            en caso de haber */
            DB::rollBack();
        }
    }

    //------------------------------------ FIN ------------------------------------//
    //---------------------- CONTROLADOR CITAS PARA USUARIOS ----------------------//

    //-----------------------------------------------------------------------------//

    //---------------------- CONTROLADOR CITAS DE DASHBOARD -----------------------//
    //---------------------------------- INICIO -----------------------------------//

    /* Cancelamos las citas del día a partir de la hora de cancelación */
    public function cancelarCitasDia(Request $request){

        $motivo = json_decode($request->getContent());
        
        $auth_user = AuthHelper::authSession(); //Obtenemos los datos del usuario que está logeado

        /*Creamos variables para envio al front mediante petición fetch*/
        $status = 200;
        $mensaje = 'OK';

        $fechasUTC = $this->obtenerHorarioUTC($auth_user->id_ccls);

        $fechaTiempoActualUTC = $fechasUTC[0];
        $fechaActual = $fechasUTC[1];
        $tiempoActual = $fechasUTC[2];        

        // Obtenemos citas del día y hora activas
        $citasDia = citasRegistrada::where('id_ccls', $auth_user->id_ccls)
            ->where('cita_fecha', '=', $fechaActual)
            ->where('cita_hora', '>', $tiempoActual)
            ->where('status', 1)
            ->where('status_conciliador','2')
            ->get();

        // Validamos si hay citas, para proceder a cancelar y envíar correos
        if(count($citasDia) > 0) {

            //Iteramos las cital del día y mandamos correo y actualizamos a cita cancelada
            foreach ($citasDia as $cita) {   
                
                try{

                    //Comenzamos transacción
                    DB::beginTransaction();

                    //Si logramos cancelar cita en la DB y todo está bien, enviamos correo de cita cancelada
                    DB::afterCommit(function () use($cita,$auth_user, $motivo){

                        try {
                            //Enviamos el correo con los datos de la reserva
                            Mail::to($cita->correo)->bcc($auth_user->buzon ? $auth_user->buzon : $auth_user->email)->send(new NotificacionCancelarFechasCCLS($cita, $motivo));
                        } catch(Exception $e) {
                            Mail::to($auth_user->buzon ? $auth_user->buzon : $auth_user->email)->send(new NotificacionCancelarFechasCCLS($cita, $motivo, True));

                        }
                    });

                    // Cancelamos cita
                    DB::table('citas_registradas')
                    ->where('cita_folio', $cita->cita_folio)
                    ->where('status', 1)
                    ->where('status_conciliador', '2')
                    ->update(['status' => '0', 'observaciones_conciliador' => $motivo, 'updated_at' => $fechaTiempoActualUTC]);

                    //Terminamos la transacción
                    DB::commit();

                }catch(\Exception $e){
                    /* En caso de error al intentar cancelar cita, 
                    regresamos a la vista con un mensaje de error y revertimos los registros fallidos, 
                    en caso de haber */
                    DB::rollBack();
                    $status = 500;
                    $mensaje = 'FAIL';
                    return redirect()->route('dashboard')->withError(__('No fue posible cancelar citas, intente más tarde.'));
                }

            }
                
        }

        $response = [
            'status' => $status,
            'message' => $mensaje,
            'data' => count($citasDia)
        ];

        return response()->json($response);

    }

    /*Conciliador configura fechas disponibles para atender citas*/
    public function configurarDisponibilidadFechasOficina(Request $request)
    {
        
        $auth_user = AuthHelper::authSession(); //Obtenemos los datos del usuario que está logeado

        /*Obtenemos las fechas de la oficina*/
        $fechasDisponibles = CitasDisponibilidadFechasOficinas::where('id_administrador', $auth_user->id)->get();

        $assets = ['chart', 'animation'];
        return view('public.citas.disponibilidad-fechas-cita', compact('assets', 'fechasDisponibles'));

    }

    /*Validamos citas agendadas*/
    public function validarFechasAgendadas(Request $request)
    {

        /*Creamos variables para envio al front mediante petición fetch*/
        $status = 200;
        $mensaje = 'OK';
        $fechasCitas = collect([]); // arreglo de objetos

        $fechasValidarDisponibles = json_decode($request->getContent());

        $auth_user = AuthHelper::authSession(); //Obtenemos los datos del usuario que está logeado

        foreach($fechasValidarDisponibles as $disponible){

            try{

                // buscamos citas registradas
                $citasRegistradas = CitasRegistrada::where('cita_fecha', $disponible)
                ->where('status', 1)
                ->where('status_conciliador', '2')
                ->where('id_ccls', $auth_user->id_ccls)
                ->get();

                $totalCitasRegistradas = count($citasRegistradas); //Contamos el número de citas agendadas y activas

                //Válida si hay citas registradas
                if($totalCitasRegistradas != 0){

                    /*Guardamos las fechas con citas agendadas */
                    $fechasCitas->push([
                        'citas' => $totalCitasRegistradas,
                        'fecha' => date("d-m-Y", strtotime($disponible))
                    ]);

                }
            
            }catch(\Exception $e){
                /* En caso de error al intentar registrar fecha, 
                regresamos a la vista con un mensaje de error y revertimos los registros fallidos, 
                en caso de haber */                
                $status = 500;
                $mensaje = 'FAIL';
                return redirect()->route('guardardisponibilidadfechas')->withError(__('No fue posible validar fechas, intente más tarde.'));
            }

        }

        $response = [
            'status' => $status,
            'message' => $mensaje,
            'data' => $fechasCitas
        ];
                
        return response()->json($response);
    }


    /**Guardamos las fechas selecionadas por el Administrador */
    public function guardarDisponiblesFechasOficina(Request $request)
    {

        /*Creamos variables para envio al front mediante petición fetch*/
        $status = 200;
        $mensaje = 'OK';        
        $fechasHoy = false;

        $fechasDisponibles = json_decode($request->getContent());
        
        $auth_user = AuthHelper::authSession(); //Obtenemos los datos del usuario que está logeado  
        
        $fechasUTC = $this->obtenerHorarioUTC($auth_user->id_ccls);
        
        $fechaTiempoActualUTC = $fechasUTC[0];
        $fechaActual = $fechasUTC[1];
        $tiempoActual = $fechasUTC[2];

        try{
        
            //Comenzamos transacción
            DB::beginTransaction();                      

            foreach($fechasDisponibles as $disponible){
                
                if($disponible != $fechaActual){

                    //Obtenemos datos de fechas por oficina
                    $existeFecha = CitasDisponibilidadFechasOficinas::where('id_administrador', $auth_user->id)
                    ->where('id_ccls', $auth_user->id_ccls)
                    ->where('fecha', $disponible)
                    ->get()->pluck('status');


                    //Obtenemos datos de las citas registradas para cancelar y enviar correos
                    $citas = CitasRegistrada::where('id_ccls', $auth_user->id_ccls)
                    ->where('cita_fecha', $disponible)
                    ->where('status', 1)
                    ->where('status_conciliador', '2')
                    ->get();                
                    
                    /*Preguntaos si la fecha existe*/
                    if(count($existeFecha) > 0){

                        $invertirStatus = $existeFecha[0]; //Variable auxiliar para invertir status
                        /*Si existe, actualizamos las fechas dado que el administrador quiere seleccionar fechas que no desea*/
                        CitasDisponibilidadFechasOficinas::where('id_administrador', $auth_user->id)
                        ->where('fecha', $disponible)
                        ->update([
                            'status' => !$invertirStatus,
                            'updated_at' => $fechaTiempoActualUTC
                        ]);

                    }else{

                        /*Si no existe, guardamos las fechas dado que el administrador quiere nuevas fechas deseadas*/
                        CitasDisponibilidadFechasOficinas::create([
                            'id_administrador' => $auth_user->id,
                            'id_ccls' => $auth_user->id_ccls,
                            'fecha' => $disponible,
                            'status' => true,
                            //'tipo' => $disponible->tipo
                            'created_at' => $fechaTiempoActualUTC,
                            'updated_at' => $fechaTiempoActualUTC
                        ]);

                    }

                    if(count($citas) > 0) {

                        //Iteramos las cital del día y mandamos correo y actualizamos a cita cancelada
                        foreach ($citas as $cita) {

                            try{

                                //Enviamos el correo con los datos de la cita
                                Mail::to($cita->correo)->bcc($auth_user->buzon ? $auth_user->buzon : $auth_user->email)->send(new NotificacionCancelarFechasCCLS($cita, 'Cita cancelada'));
                                
                            } catch(Exception $e) {
                                Mail::to($auth_user->buzon ? $auth_user->buzon : $auth_user->email)->send(new NotificacionCancelarFechasCCLS($cita, 'Cita cancelada', True));
                            }
                            
                
                            // Cancelamos cita
                            DB::table('citas_registradas')
                            ->where('cita_folio', $cita->cita_folio)
                            ->where('status', 1)
                            ->where('status_conciliador', '2')
                            ->update(['status' => '0', 'updated_at' => $fechaTiempoActualUTC]);

                        }
                            
                    }


                }

            }

            //Terminamos la transacción
            DB::commit();
            
        }catch(\Exception $e){
            /* En caso de error al intentar registrar fecha, 
            regresamos a la vista con un mensaje de error y revertimos los registros fallidos, 
            en caso de haber */
            DB::rollBack();
            $status = 502;
            $mensaje = 'FAIL';
            return redirect()->route('guardardisponibilidadfechas')->withError(__('No fue posible guardar fechas, intente más tarde.'));
        }
        
        $response = [
            'status' => $status,
            'message' => $mensaje,            
        ];
                
        return response()->json($response);

    }

    /* Administrador configura disponibilidad para atender citas*/
    public function configurarDisponibilidadOficina(Request $request)
    {

        $auth_user = AuthHelper::authSession(); //Obtenemos los datos del usuario que está logeado

        $fechasUTC = $this->obtenerHorarioUTC($auth_user->id_ccls);

        $fechaTiempoActualUTC = $fechasUTC[0];
        $fechaActual = $fechasUTC[1];
        $tiempoActual = $fechasUTC[2];

        /*Obtenemos la configuración de días*/
        $disponbilidadOficina = CitasConfiguracionOficinas::where('created_at', function ($query) {
            $auth_user = AuthHelper::authSession();
            $query->selectRaw('MAX(created_at)')
                  ->from('citas_configuracion_oficinas as b')
                  ->where('b.id_ccls', $auth_user->id_ccls)
                  ->where('status', 1);
        })->get()->first();
        
        $assets = ['chart', 'animation'];
        return view('public.citas.configuracion-cita', compact('assets', 'disponbilidadOficina'));
    }

    /**Obtenemos la cita máxima agendada */
    public function validaConfiguracion(Request $request)
    {

        $auth_user = AuthHelper::authSession(); //Obtenemos los datos del usuario que está logeado

        /*Creamos variables desde front mediante petición fetch*/
        $status = 200;
        $mensaje = 'OK';
        $texto = '';        
        $aplicaConfigDateMax2 = null;
        $conciliadores = 50;
        $auxiliares = 50;

        $fechasUTC = $this->obtenerHorarioUTC($auth_user->id_ccls);

        $fechaTiempoActualUTC = $fechasUTC[0];
        $fechaActual = $fechasUTC[1];
        $tiempoActual = $fechasUTC[2];
        
        // Datos de configuración
        $oficinaDisponibles = json_decode($request->getContent());

        // Validamos si hay fecha de creación
        if($oficinaDisponibles->created_at == false){

            $fechaConfigurada = $fechaTiempoActualUTC;

        }else{

            $fechaConfigurada = date('Y-m-d H:i:s', strtotime($oficinaDisponibles->created_at));

        }        

        // Verificamos si es la primera vez que va a configurar
        /* Bloque de días */
        $config = CitasConfiguracionOficinas::where('id_ccls', $auth_user->id_ccls)
        ->where('id_administrador', $auth_user->id)
        ->where('status', 1)
        ->get();

        if(count($config) === 0){

            $status = 202;
           
            $response = [
                'status' => $status,
                'message' => $mensaje,
                'text' => $texto,
                'data' => $fechaActual,
                'created' => $fechaConfigurada
            ];
        
            return response()->json($response);
        }

        foreach($oficinaDisponibles->diasDiponibles as $dias){
            switch($dias->dia){
                case 'lunes':
                    $lunes = $dias->disponible;
                break;
                case 'martes':
                    $martes = $dias->disponible;
                break;
                case 'miércoles':
                    $miercoles = $dias->disponible;
                break;
                case 'jueves':
                    $jueves = $dias->disponible;
                break;
                case 'viernes':
                    $viernes = $dias->disponible;
                break;
            }
        }

        //Verifcamos si hay cambios con días a bloquear
        $verificarDias = CitasConfiguracionOficinas::where('id_ccls', $auth_user->id_ccls)
        ->where('id_administrador', $auth_user->id)
        ->where('status', 1)
        ->where('created_at', $fechaConfigurada)
        ->where('status_lunes', $lunes)
        ->where('status_martes', $martes)
        ->where('status_miercoles', $miercoles)
        ->where('status_jueves', $jueves)
        ->where('status_viernes', $viernes)
        ->get();

        /* Bloque de horarios */

        //Verifica si hay cambios en los horarios de atención y oficina
        $horariosDefinidos = CitasConfiguracionOficinas::where('id_ccls', $auth_user->id_ccls)
        ->where('id_administrador', $auth_user->id)
        ->where('status', 1 )
        ->where('hora_cita_inicio', $oficinaDisponibles->cclApertura)
        ->where('hora_cita_fin', $oficinaDisponibles->cclCierre)
        ->where('minutos_cita', $oficinaDisponibles->rangoMinutos)
        ->where('hora_comida_inicio', $oficinaDisponibles->cclComidaInicio)
        ->where('hora_comida_fin', $oficinaDisponibles->cclComidaFin)
        ->where('created_at', $fechaConfigurada)
        ->get();
        
        //Obtenemos datos de configuración de oficina únicamente usuarios
        $configUsuarios = CitasConfiguracionOficinas::where('id_ccls', $auth_user->id_ccls)
            ->where('total_conciliadores', $conciliadores)
            ->where('total_auxiliares', $auxiliares)
            ->where('status', 1)
            ->where('created_at', $fechaConfigurada)
            ->get();

        /*Obtenemos todos los usuarios del perfil*/ 
        $usuariosConciliadores = User::where('id_ccls', $auth_user->id_ccls)
        ->where('status', '1')
        ->where('perfil', 'conciliador')
        ->count();

        $usuariosAuxiliares = User::where('id_ccls', $auth_user->id_ccls)
        ->where('perfil', 'auxiliar')
        ->count();

        //Forzamos a convertir el dato en número
        $intConciliadores = (Int)$conciliadores;
        $intAuxiliares = (Int)$auxiliares;

        //Verificamos si los usuarios configurados son mayores a los registrados
        if($usuariosConciliadores > $intConciliadores || $usuariosAuxiliares > $intAuxiliares){

            $status = 201;

            // notificamos al usuario que no fue posible aplicar la configuración
            $response = [
                'status' => $status,
                'message' => $mensaje,
                'text' => $texto
            ];
        
            return response()->json($response);

        }

        /* Bloque de meses */
        //Verificamo si hay cambios en la configuración de meses
        $mesesConfigurados = CitasConfiguracionOficinas::where('id_ccls', $auth_user->id_ccls)
        ->where('id_administrador', $auth_user->id)
        ->where('status', 1)
        ->where('meses_cita', $oficinaDisponibles->rangoMeses)
        ->where('created_at', $fechaConfigurada)
        ->get();

        
        //Verificamos si hay cambios
        if((count($mesesConfigurados) === 0 || count($configUsuarios) === 0) && count($verificarDias) === 1 && count($horariosDefinidos) === 1){
            
            //Solo cambio meses u usuarios por lo tanto no mostramos notificación
            $status = 202;

        }

        if(count($verificarDias) === 0 || count($horariosDefinidos) === 0){

            // Obtenemos el las configuraciones en citas agendadas
            $configuraciones = DB::table('citas_configuracion_oficinas as t1')
                ->select('t1.*', 't2.id_configuracion', 't2.status as status_cita', 't2.status_conciliador')
                ->leftJoin('citas_registradas as t2', 't1.id', '=', 't2.id_configuracion')
                ->where('t1.id_ccls', $auth_user->id_ccls)
                ->where('t1.id_administrador', $auth_user->id)
                ->where('t1.status', 1)
                ->where('t2.status', 1)
                ->where('t2.status_conciliador', '=', '2')
                ->distinct()
                ->get();

            // verificamos si tiene 2 configuraciones, entonces noficamos el límite
            if(count($configuraciones) >= 2){
                
                $status = 203;
            
                $response = [
                    'status' => $status,
                    'message' => $mensaje,
                    'text' => $texto,
                    'data' => $fechaActual,
                    'created' => $fechaConfigurada
                ];
            
                return response()->json($response);

            }

            //Si hay cambios aquí, se entiende que es nueva configuración y se crean los nuevo parametros
            //Cambio el núcleo de hora y atención a citas por lo tanto mostramos notificación
            //Obtenemos la cita de acuerdo a la fecha máxima
            $citasActivas = CitasRegistrada::where('cita_fecha', function($query) {
                $auth_user = AuthHelper::authSession();
                $query->selectRaw('MAX(cita_fecha)')
                    ->from('citas_registradas as b')
                    ->where('b.id_ccls', $auth_user->id_ccls)
                    ->where('b.status', 1)
                    ->where('b.status_conciliador', '=', '2');
            })->get()->first();

            if(isset($citasActivas)){

                //Sumamos un día a la fecha máxima para aplicar configuración
                $citaMaxima = date_create($citasActivas->cita_fecha);
                $aplicaConfigDateMax = date_add($citaMaxima, date_interval_create_from_date_string('+1 days'))->format('Y-m-d');

                // Si fecha de aplicación es igual a la guardada, entonces es actualización
                if($aplicaConfigDateMax === $oficinaDisponibles->aplica){

                    $status = 202;

                    $response = [
                        'status' => $status,
                        'message' => $mensaje,
                        'text' => $texto,
                        'data' => $oficinaDisponibles->aplica,
                        'created' => $fechaConfigurada
                    ];

                    return response()->json($response);

                }

                //formato para JS
                $aplicaConfigDateMax2 = date('d-m-Y', strtotime($aplicaConfigDateMax));
                
                $texto = "Esta nueva configuración se aplicará el $aplicaConfigDateMax2.";

            }

        }

        $response = [
            'status' => $status,
            'message' => $mensaje,
            'text' => $texto,
            'data' => $aplicaConfigDateMax2 != null ? $aplicaConfigDateMax2 : $fechaActual,
            'created' => $fechaConfigurada
        ];
      
        return response()->json($response);
    }

    /* Guardamos las horas seleccionas para oficina*/
    public function guardarHorasDisponiblesOficina(Request $request)
    {

        $auth_user = AuthHelper::authSession(); //Obtenemos los datos del usuario que está logeado
        $oficinaDisponibles = json_decode($request->getContent());

        /*Creamos variables desde front mediante petición fetch*/
        $status = 200;
        $mensaje = 'OK';
        $lunes = false;
        $martes = false;
        $miercoles = false;
        $jueves = false;
        $viernes = false;
        $diasCitas = collect([]);
        $conciliadores = 50;
        $auxiliares = 50;
        $texto = '';

        // Fecha de creacióon original
        $fechaConfigurada = $oficinaDisponibles->created;        

        //Fecha de aplicación de configuración
        $aplica = date('Y-m-d', strtotime($oficinaDisponibles->aplica));

        $fechasUTC = $this->obtenerHorarioUTC($auth_user->id_ccls);

        $fechaTiempoActualUTC = $fechasUTC[0];
        $fechaActual = $fechasUTC[1];
        $tiempoActual = $fechasUTC[2];

        foreach($oficinaDisponibles->diasDiponibles as $dias){
            switch($dias->dia){
                case 'lunes':
                    $lunes = $dias->disponible;
                break;
                case 'martes':
                    $martes = $dias->disponible;
                break;
                case 'miércoles':
                    $miercoles = $dias->disponible;
                break;
                case 'jueves':
                    $jueves = $dias->disponible;
                break;
                case 'viernes':
                    $viernes = $dias->disponible;
                break;
            }
        }

        //Validamos si la oficina ya existe
        $oficina = CitasConfiguracionOficinas::where('id_ccls', $auth_user->id_ccls)
        ->where('id_administrador', $auth_user->id)
        ->where('status', 1)
        ->where('created_at', $fechaConfigurada)
        ->count();

        //Obtenemos el registró máximo de la oficina        
        $disponbilidadOficina = CitasConfiguracionOficinas::where('created_at', function ($query) {
            $auth_user = AuthHelper::authSession();
            $query->selectRaw('MAX(created_at)')
                  ->from('citas_configuracion_oficinas as b')
                  ->where('b.id_ccls', $auth_user->id_ccls)
                  ->where('status', 1);
        })->get()->first();
        
        //Guardamos la configuración la primera vez
        if($oficina === 0) {

            try{

                //Comenzamos transacción
                DB::beginTransaction();

                CitasConfiguracionOficinas::create([
                    'id_administrador' => $auth_user->id,
                    'id_ccls' => $auth_user->id_ccls,
                    'total_conciliadores' => $conciliadores,
                    'total_auxiliares' => $auxiliares,
                    'status_lunes' => $lunes,
                    'status_martes' => $martes,
                    'status_miercoles' => $miercoles,
                    'status_jueves' => $jueves,
                    'status_viernes' => $viernes,
                    'hora_cita_inicio' => $oficinaDisponibles->cclApertura,
                    'hora_cita_fin' => $oficinaDisponibles->cclCierre,
                    'minutos_cita' => $oficinaDisponibles->rangoMinutos,
                    'meses_cita' => $oficinaDisponibles->rangoMeses,
                    'hora_comida_inicio' => $oficinaDisponibles->cclComidaInicio,
                    'hora_comida_fin' => $oficinaDisponibles->cclComidaFin,
                    'status' => 1,
                    'aplica' => $aplica,
                    'created_at' => $fechaTiempoActualUTC,
                    'updated_at' => $fechaTiempoActualUTC,
                ]);
    
                foreach($oficinaDisponibles->horariosDisponibles as $horarios){
                    CitasDisponibilidadOficinas::create([
                        'id_administrador' => $auth_user->id,
                        'id_ccls' => $auth_user->id_ccls,
                        'horario' => $horarios->hora,
                        'status' => $horarios->status,
                        'aplica' => $aplica,
                        'created_at' => $fechaTiempoActualUTC,
                        'updated_at' => $fechaTiempoActualUTC,
                    ]);
                }

                //Terminamos la transacción
                DB::commit();

            }catch(\Exception $e){
                DB::rollBack();
                $status = 500;
                $mensaje = 'FAIL';
                return redirect()->route('guardardisponibilidad')->withError(__('No fue posible guardar su configuración, intente más tarde.'));
            }

            $response = [
                'status' => $status,
                'message' => $mensaje,
            ];
          
            return response()->json($response);
        
        }else{

            //Guardamos/actualizamos la configuración necesarias
            try{
                
                //Comenzamos transacción
                DB::beginTransaction();

                /* Bloque de horarios */

                //Verifica si hay cambios en los horarios de atención y oficina
                $horariosDefinidos = CitasConfiguracionOficinas::where('id_ccls', $auth_user->id_ccls)
                ->where('id_administrador', $auth_user->id)
                ->where('status', 1 )
                ->where('hora_cita_inicio', $oficinaDisponibles->cclApertura)
                ->where('hora_cita_fin', $oficinaDisponibles->cclCierre)
                ->where('minutos_cita', $oficinaDisponibles->rangoMinutos)
                ->where('hora_comida_inicio', $oficinaDisponibles->cclComidaInicio)
                ->where('hora_comida_fin', $oficinaDisponibles->cclComidaFin)
                ->where('created_at', $fechaTiempoActualUTC)
                ->get();

                //Verica si existen citas para la oficina
                $exitenCitas = CitasRegistrada::where('id_ccls', $auth_user->id_ccls)
                ->get();

                //Si hay cambios aquí y hay citas, se entiende que es nueva configuración y se crean los nuevo parametros
                if(count($horariosDefinidos) === 0 && count($exitenCitas) > 0 && $disponbilidadOficina->aplica !== $oficinaDisponibles->aplica){
                    
                    //Obtenemos datos de configuración de oficina únicamente usuarios
                    $configUsuarios = CitasConfiguracionOficinas::where('id_ccls', $auth_user->id_ccls)
                        ->where('total_conciliadores', $conciliadores)
                        ->where('total_auxiliares', $auxiliares)
                        ->where('status', 1)
                        ->where('created_at', $fechaTiempoActualUTC)
                        ->get();

                    /*Obtenemos todos los usuarios del perfil*/ 
                    $usuariosConciliadores = User::where('id_ccls', $auth_user->id_ccls)
                    ->where('status', '1')
                    ->where('perfil', 'conciliador')
                    ->count();

                    $usuariosAuxiliares = User::where('id_ccls', $auth_user->id_ccls)
                    ->where('perfil', 'auxiliar')
                    ->count();

                    //Forzamos a convertir el dato en número
                    $intConciliadores = (Int)$conciliadores;
                    $intAuxiliares = (Int)$auxiliares;  

                    //Verificamos si los usuarios configurados son mayores a los registrados
                    if($usuariosConciliadores > $intConciliadores || $usuariosAuxiliares > $intAuxiliares){

                        $status = 201;
                        $mensaje = 'OK';

                    }else{
                        
                        // Guardamos la nueva configuración

                        CitasConfiguracionOficinas::create([
                            'id_administrador' => $auth_user->id,
                            'id_ccls' => $auth_user->id_ccls,
                            'total_conciliadores' => $conciliadores,
                            'total_auxiliares' => $auxiliares,
                            'status_lunes' => $lunes,
                            'status_martes' => $martes,
                            'status_miercoles' => $miercoles,
                            'status_jueves' => $jueves,
                            'status_viernes' => $viernes,
                            'hora_cita_inicio' => $oficinaDisponibles->cclApertura,
                            'hora_cita_fin' => $oficinaDisponibles->cclCierre,
                            'minutos_cita' => $oficinaDisponibles->rangoMinutos,
                            'meses_cita' => $oficinaDisponibles->rangoMeses,
                            'hora_comida_inicio' => $oficinaDisponibles->cclComidaInicio,
                            'hora_comida_fin' => $oficinaDisponibles->cclComidaFin,
                            'status' => 1,
                            'aplica' => $aplica,
                            'created_at' => $fechaTiempoActualUTC,
                            'updated_at' => $fechaTiempoActualUTC,
                        ]);
            
                        foreach($oficinaDisponibles->horariosDisponibles as $horarios){
                            CitasDisponibilidadOficinas::create([
                                'id_administrador' => $auth_user->id,
                                'id_ccls' => $auth_user->id_ccls,
                                'horario' => $horarios->hora,
                                'status' => $horarios->status,
                                'aplica' => $aplica,
                                'created_at' => $fechaTiempoActualUTC,
                                'updated_at' => $fechaTiempoActualUTC,
                            ]);
                        }

                    }
    
                }else{

                    /* Bloque de días */

                    //Verifcamos su hay cambios con días a bloquear
                    $verificarDias = CitasConfiguracionOficinas::where('id_ccls', $auth_user->id_ccls)
                    ->where('id_administrador', $auth_user->id)
                    ->where('status', 1)
                    ->where('created_at', $fechaConfigurada)
                    ->where('status_lunes', $lunes)
                    ->where('status_martes', $martes)
                    ->where('status_miercoles', $miercoles)
                    ->where('status_jueves', $jueves)
                    ->where('status_viernes', $viernes)
                    ->get();

                    //Verificamos si hay cambios unicamente en días
                    if(count($verificarDias) === 0){
                        //Actualizamos días configurados unicamente
                        CitasConfiguracionOficinas::where('id_ccls', $auth_user->id_ccls)
                        ->where('id_administrador', $auth_user->id)
                        ->where('status', 1)
                        ->where('created_at', $fechaConfigurada)
                        ->update([
                            'status_lunes' => $lunes,
                            'status_martes' => $martes,
                            'status_miercoles' => $miercoles,
                            'status_jueves' => $jueves,
                            'status_viernes' => $viernes,
                            'updated_at' => $fechaTiempoActualUTC,
                        ]);

                        //$texto = "Esta configuración se aplicará hasta: $aplicaConfigDateMax. <br>Si desea aplicarlos, lo antes posible, haga uso del menú <b>Fechas</b>.";

                    }

                    /* Bloque de meses */

                    $mesesConfigurados = CitasConfiguracionOficinas::where('id_ccls', $auth_user->id_ccls)
                    ->where('id_administrador', $auth_user->id)
                    ->where('status', 1)
                    ->where('meses_cita', $oficinaDisponibles->rangoMeses)
                    ->where('created_at', $fechaConfigurada)
                    ->get();

                    if(count($mesesConfigurados) === 0){

                        //Actualizamos meses configurados unicamente
                        CitasConfiguracionOficinas::where('id_ccls', $auth_user->id_ccls)
                        ->where('id_administrador', $auth_user->id)
                        ->where('status', 1)
                        ->where('created_at', $fechaConfigurada)
                        ->update([                        
                            'meses_cita' => $oficinaDisponibles->rangoMeses,
                            'updated_at' => $fechaTiempoActualUTC,
                        ]);

                    }

                    /* Bloque de horarios y tiempo de ateción citas*/

                    $atencionHorariosCitas = CitasConfiguracionOficinas::where('id_ccls', $auth_user->id_ccls)
                    ->where('id_administrador', $auth_user->id)
                    ->where('status', 1)
                    ->where('hora_cita_inicio', $oficinaDisponibles->cclApertura)
                    ->where('hora_cita_fin', $oficinaDisponibles->cclCierre)
                    ->where('minutos_cita', $oficinaDisponibles->rangoMinutos)                    
                    ->where('hora_comida_inicio', $oficinaDisponibles->cclComidaInicio)
                    ->where('hora_comida_fin', $oficinaDisponibles->cclComidaFin)
                    ->where('created_at', $fechaConfigurada)
                    ->get();

                    //Verificamos si hay cambios en horarios de atención
                    if ( count( $atencionHorariosCitas ) === 0 ){

                        // Eliminamos los hrarios para aplicar los nuevos horarios de citas
                        $horariosDisponibles = CitasDisponibilidadOficinas::where('id_ccls', $auth_user->id_ccls)
                        ->where('aplica', '=', $aplica)
                        ->delete();

                        // Si hay registros eliminados, aplicamos los nuevos
                        if( $horariosDisponibles > 0 ) {
                            
                            //Actualizamos horarios de atención configurados unicamente
                            CitasConfiguracionOficinas::where('id_ccls', $auth_user->id_ccls)
                            ->where('id_administrador', $auth_user->id)
                            ->where('status', 1)
                            ->where('created_at', $fechaConfigurada)
                            ->update([                        
                                'hora_cita_inicio' => $oficinaDisponibles->cclApertura,
                                'hora_cita_fin' => $oficinaDisponibles->cclCierre,
                                'minutos_cita' => $oficinaDisponibles->rangoMinutos,                            
                                'hora_comida_inicio' => $oficinaDisponibles->cclComidaInicio,
                                'hora_comida_fin' => $oficinaDisponibles->cclComidaFin,
                                'aplica' => $aplica,
                                'updated_at' => $fechaTiempoActualUTC,
                            ]);

                            //Creamos los nuevos horarios de atención de citas
                            foreach($oficinaDisponibles->horariosDisponibles as $horarios){
                                CitasDisponibilidadOficinas::create([
                                    'id_administrador' => $auth_user->id,
                                    'id_ccls' => $auth_user->id_ccls,
                                    'horario' => $horarios->hora,
                                    'status' => $horarios->status,
                                    'aplica' => $aplica,
                                    'created_at' => $fechaTiempoActualUTC,
                                    'updated_at' => $fechaTiempoActualUTC
                                ]);
                            }

                        }

                    }                    

                }

                //Terminamos la transacción
                DB::commit();

            }catch(\Exception $e){
                DB::rollBack();
                $status = 500;
                $mensaje = 'FAIL';
                return redirect()->route('guardardisponibilidad')->withError(__('No fue posible guardar configuración de días, intente más tarde.'));
            }

            $response = [
                'status' => $status,
                'message' => $mensaje,
                'text' => $texto
            ];
          
            return response()->json($response);
        }

    }

    /* Vista para la atentación a citas*/
    public function atencionCitasConciliador(Request $request)
    {
        $auth_user = AuthHelper::authSession(); //Obtenemos los datos del usuario que está logeado

        $fechasUTC = $this->obtenerHorarioUTC($auth_user->id_ccls);

        $fechaTiempoActualUTC = $fechasUTC[0];
        $fechaActual = $fechasUTC[1];
        $tiempoActual = $fechasUTC[2];

        $inicioSemana = now()->startOfWeek()->format('Y-m-d');
        $finSemana = now()->endOfWeek()->format('Y-m-d');

        if($auth_user->perfil != 'auxiliar'){
            // Obtenemos datos de citas por conciliador
            $listar_citas = DB::table('citas_registradas as t1')
                ->select('t1.cita_folio', 't1.nombre', 't1.apellidos', 't1.correo', 't1.celular', 't1.cita_fecha', 't1.cita_hora', 't1.status', 't1.observaciones', 't1.id_conciliador', 't1.status_conciliador', 't1.observaciones_conciliador' )
                ->where('t1.id_ccls', '=', $auth_user->id_ccls)
                ->where('t1.status', '=', 1)
                ->where('t1.status_conciliador', '=', '2')
                ->whereBetween('t1.cita_fecha', [$inicioSemana, $finSemana])
                ->orderBy('t1.cita_fecha', 'asc')
                ->get();
        }else{
            // Obtenemos datos de citas por oficina de la semana actual
            $listar_citas = DB::table('citas_registradas as t1')
                ->select('t1.cita_folio', 't1.nombre', 't1.apellidos', 't1.correo', 't1.celular', 't1.cita_fecha', 't1.cita_hora', 't1.status', 't1.observaciones', 't1.id_conciliador', 't1.status_conciliador', 't1.observaciones_conciliador')
                ->where('t1.id_ccls', '=', $auth_user->id_ccls)
                ->where('t1.status', '=', 1)
                ->where('t1.status_conciliador', '=', '2')
                ->whereBetween('t1.cita_fecha', [$inicioSemana, $finSemana])
                ->orderBy('t1.cita_fecha', 'asc')
                ->get();
        }

        $assets = ['chart', 'animation'];
        return view('public.citas.atencion-citas', compact('listar_citas','assets', 'fechaActual'));
    }

    /*Conciliador cancela o confirma cita de empleador*/
    public function accionCitaConciliador(Request $request){
        
        $auth_user = AuthHelper::authSession(); //Obtenemos los datos del usuario que está logeado

        // Obtenemos la oficina del conciliador/administrador
        $oficina = $auth_user->id_ccls;

        $fechasUTC = $this->obtenerHorarioUTC($oficina);

        $fechaTiempoActualUTC = $fechasUTC[0];
        $fechaActual = $fechasUTC[1];
        $tiempoActual = $fechasUTC[2];
        
        
        /* Obtenemos el correo del administrador para enviar Copia oculta */        
        $correoCcl = User::where('id_ccls', $oficina)
        ->where('perfil', '=', 'administrador')
        ->where('status', '=', '1')
        ->first();

        try{

            //Comenzamos transacción
            DB::beginTransaction();

            DB::table('citas_registradas')
            ->where('cita_folio', $request->accionFolio)
            /* ->where('id_conciliador', $auth_user->id) */
            ->update(['status_conciliador' => $request->accionCitaStatus, 'status' => $request->accionCitaStatus, 'observaciones_conciliador' => $request->accionCita, 'updated_at' => $fechaTiempoActualUTC]);

            if($request->accionCitaStatus == '1'){
                $mensaje = 'atendida';
            }
    
            if($request->accionCitaStatus == '0'){
                $mensaje = 'cancelada';
            }

            // Si logramos guardar en la DB y todo está bien, Enviamos correo de cita atentida o cancelada
            DB::afterCommit(function () use($request, $mensaje, $correoCcl){

                try{
                    /*Enviamos el correo con los datos de la reserva*/
                    Mail::to(request('accionCorreo'))->bcc($correoCcl->buzon ? $correoCcl->buzon : $correoCcl->email)->send(new NotificacionCitaAccionCCLS($request, $mensaje));

                } catch(Exception $e) {                    
                    Mail::to($correoCcl->buzon ? $correoCcl->buzon : $correoCcl->email)->send(new NotificacionCitaAccionCCLS($request, $mensaje, True));
                }
                
            });

            //Terminamos la transacción
            DB::commit();

        }catch(\Exception $e){            
             /* En caso de error al intentar registrar cita, 
            regresamos a la vista con un mensaje de error y revertimos los registros fallidos, 
            en caso de haber */
            DB::rollBack();
            return redirect()->route('atencioncitas')->withError(__('No fue posible cancelar la cita, intente más tarde.'));
        }
                
        return redirect()->route('atencioncitas')->withSuccess(__("Cita $mensaje"));

    }

    //------------------------------------ FIN ------------------------------------//
    //---------------------- CONTROLADOR CITAS DE DASHBOARD -----------------------//


    /*Función de apoyo para obtener días de semana */
    public function obtenerDiaSemana($fecha){

        /*Obtenemos el día seleccionado por fullCaledar*/
        $dia = date("w", strtotime($fecha));

        switch ($dia) {
            case 1:
                $diaSemana = 'lunes';
                break;
            case 2:
                $diaSemana = 'martes';
                break;
            case 3:
                $diaSemana = 'miércoles';
                break;
            case 4:
                $diaSemana = 'jueves';
                break;
            case 5:
                $diaSemana = 'viernes';
                break;            
        }   

        return $diaSemana;

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

    /*generar folio */
    private function obtenerFolio($nombre, $aps, $fecha, $hora, $segundos)
    {

        //Obtiene datos
        $nombre = substr($nombre,0,1);
        $aps = substr($aps,0,1);
        $fecha = $fecha;
        $hora = str_replace(":", "", $hora);
        $segundos = str_replace(":", "", $segundos);

        $diaSemana = "";

        //Obtenemos 5 carácteres aleatorios
        $prefolio = $this->folioRandom(5);

        //digítos que conforman folio
        $d=date('w', strtotime($fecha));
        $m=date("n",strtotime($fecha));
        $a=date("Y",strtotime($fecha));
        $diaSemana = substr(strtoupper($this->obtenerDiaSemana($fecha)),0,1);

        //Folio compuestor los siguientes carácteres
        $folio = strtolower($nombre) . $d . strtoupper($aps) . $m . $prefolio . substr($hora, 0, 2) . $diaSemana . substr($hora, 2, 2) . substr($segundos, 4, 2);

        return $folio;
        
    }

    /*Genera carácteres aleatorio*/
    private function folioRandom($num) {
        $cadena = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789_+-";
        $cadenaLength = strlen($cadena);
        $resultado = "";
        
        // Convertir la cadena en un array para facilitar la verificación
        $cadenaArray = str_split($cadena);
        $availableCharacters = $cadenaArray;
    
        while (strlen($resultado) < $num) {
            // Obtener un índice aleatorio de los caracteres disponibles
            $randomIndex = array_rand($availableCharacters);
            $digito = $availableCharacters[$randomIndex];
            
            // Si el dígito no está en el resultado, añadirlo
            if (strpos($resultado, $digito) === false) {
                $resultado .= $digito;
                // Eliminar el carácter usado de los disponibles
                unset($availableCharacters[$randomIndex]);
                $availableCharacters = array_values($availableCharacters); // Reindexar array
            }
            
            // Si no quedan suficientes caracteres únicos para completar la longitud requerida, salir del bucle
            if (count($availableCharacters) < $num - strlen($resultado)) {
                break;
            }
        }
        
        return $resultado;
    }

}
