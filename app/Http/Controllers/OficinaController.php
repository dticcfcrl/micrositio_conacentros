<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\AuthHelper;
use App\Models\CitasConfiguracionOficinas;
use App\Models\CitasDisponibilidadFechasOficinas;
use App\Models\CitasRegistrada;
use App\Models\CitasDisponibilidadOficinas;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\NotificacionEliminarCCLS;
use App\Mail\NotificacionEliminarCCLSUsuarios;
use Exception;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class OficinaController extends Controller
{
    //Controles para oficinas

    //Listar oficinas
    public function listarOficinas(Request $request)
    {

        $auth_user = AuthHelper::authSession();

        if($auth_user->perfil === 'superusuario'){
            $oficinas = DB::table('ccls as t1')->where('status', 1)->get();
        }else{
            $oficinas = DB::table('ccls as t1')->where('id', $auth_user->id_ccls)->where('status', 1)->get();
        }
        
        return view('public.oficinas.listar-oficinas', compact("oficinas"));

    }

    //Agregar oficina
    public function agregarOficina(Request $request)
    {

        $auth_user = AuthHelper::authSession();

        $estados = DB::table('estados as t1')->get()->pluck('nombre', 'clave');

        return view('public.oficinas.crear-editar-oficina', compact('estados'));
    }

    // Obtener municipio
    public function entidadMunicipio($entidadSeleccionada)
    {
        $municipio = DB::table('municipios as t1')->select('t1.nombre')
            ->where('t1.estado', $entidadSeleccionada)->get();

        return $municipio;
    }

    // Guardamos los datos de oficina nueva
    public function guardarOficina(Request $request)
    {

        // Validamos campos
        $request->validate([
            "estado" => 'required',
            "municipio" => 'required',
            "ambito" => 'required',
            "direccion" => 'required',
            "url" => 'required',
            "latitud" => 'required',
            "longitud" => 'required',
            "contacto" => 'string',
            "cp" => 'required|min:5|max:5',
            "pagina" => 'required',
            "zona" => 'required',
        ]);

        // Obtenemos el nombre del estado de acuerdo a su clave
        $estado = DB::table('estados as t1')
        ->where('t1.clave', $request->estado)
        ->get()
        ->first();

        try{

            //Comenzamos transacción
            DB::beginTransaction();
            if($request->ambito == "Federal"){
                $liga = "https://conciliacion.centrolaboral.gob.mx/asesoria/seleccion";
            }else{
                $liga= $request->liga;
            }
            //dd($liga);

            //Gudarmoda datos de oficina
            DB::table('ccls')->insert([
                'estado' => $estado->nombre,
                'municipio' => $request->municipio,
                'ambito' => $request->ambito,
                'direccion' => $request->direccion,
                'url_google' => $request->url,
                'lat' => $request->latitud,
                'long' => $request->longitud,
                'contacto' => $request->contacto,
                'cp' => $request->cp,
                'link' => $request->pagina,
                'zona_horaria' => $request->zona,
                'status' => 1,
                'liga_cita' => $liga,
                'liga_cita_local' => $request->ligalocal,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            //Terminamos la transacción
            DB::commit();

        }catch(\Exception $e){
            /* En caso de error al intentar agregar oficina, 
            regresamos a la vista con un mensaje de error y revertimos los registros fallidos, 
            en caso de haber */
            DB::rollBack();
            return redirect()->route('agregaroficina')->withError(__('No fue posible agregar oficina, intente más tarde.'));
        }

        return redirect()->route('listaroficinas')->withSuccess(__("Oficina agregada con éxito"));

    }

    // Obtenemos oficina a editar
    public function editarOficina($id)
    {

        $auth_user = AuthHelper::authSession();

        if(($auth_user->id_ccls === $id && $auth_user->perfil === 'administrador') || $id && $auth_user->perfil === 'superusuario'){

            // Variable auxiliar que controla el bloqueo de cambios de datos de oficina
            $bloqueo = false;

            // En caso de haber tenido cita bloqueamos campos que no pueden editar, si no hay citas registradas, podemos cambir datos
            $citasRegistradas = CitasRegistrada::where('id_ccls', $id)->count();

            // Verificamos si hay citas par asignar bloqueo
            if($citasRegistradas > 0 ){
                $bloqueo = true;
            }

            // Obtenemos la oficina solicitada
            $oficina = DB::table('ccls')->where('id', $id)->get();

            // Obtenemos la clave del estado sobre la oficina seleccionada
            $estado_clave = DB::table('estados as t1')->where('nombre', $oficina[0]->estado)->get()->pluck('clave');

            // Obtenemos datos de estado
            $estados = DB::table('estados as t1')->get()->pluck('nombre', 'clave');
            
            return view('public.oficinas.crear-editar-oficina', compact('id', 'estados', 'oficina', 'estado_clave', 'bloqueo'));

        }else{
            return redirect()->route('listaroficinas')->withError(__('No tiene autorización para editar datos de otras oficinas.'));
        }

    }

    // Actualizamos oficina
    public function actualizarOficina(Request $request)
    {

        $fechasUTC = $this->obtenerHorarioUTC($request->id);
        $auth_user = AuthHelper::authSession();

        $fechaTiempoActualUTC = $fechasUTC[0];
        $fechaActual = $fechasUTC[1];
        $tiempoActual = $fechasUTC[2];

        if(($auth_user->id_ccls === $request->id && $auth_user->perfil === 'administrador') || $request->id && $auth_user->perfil === 'superusuario'){

            // Validamos campos
            $request->validate([
                "estado" => 'required',
                "municipio" => 'required',
                "ambito" => 'required',
                "direccion" => 'required',
                "url" => 'required',
                "latitud" => 'required',
                "longitud" => 'required',
                "contacto" => 'string',
                "cp" => 'required|min:5|max:5',
                "pagina" => 'required',
                "zona" => 'required',
            ]);

            // Obtenemos valor de estado por número
            if($request->estado <= 32){
                // Obtenemos el nombre del estado de acuerdo a su clave
                $estado = DB::table('estados as t1')
                ->where('t1.clave', $request->estado)
                ->get()
                ->first();

                $edo = $estado->nombre;

            }else{

                $edo = $request->estado;
            }

            if($auth_user->perfil === 'administrador') {
                $ccl = DB::table('ccls')->where('id', $request->id)->get()->first();
                $edo = $ccl->estado;
                $municipio = $ccl->municipio;
                $ambito = $ccl->ambito;
            } else {
                $municipio = $request->municipio;
                $ambito = $request->ambito;
            }

            try{

                //Comenzamos transacción
                DB::beginTransaction();
                
                //Gudarmoda datos de oficina
                DB::table('ccls')->where('id', $request->id)
                ->update([
                    'estado' => $edo,
                    'municipio' => $municipio,
                    'ambito' => $ambito,
                    'direccion' => $request->direccion,
                    'url_google' => $request->url,
                    'lat' => $request->latitud,
                    'long' => $request->longitud,
                    'contacto' => $request->contacto,
                    'cp' => $request->cp,
                    'link' => $request->pagina,
                    'liga_cita' => $request->liga,
                    'liga_cita_local' => $request->ligalocal,
                    'zona_horaria' => $request->zona,
                    'updated_at' => $fechaTiempoActualUTC
                ]);

                //Terminamos la transacción
                DB::commit();

            }catch(\Exception $e){
                /* En caso de error al intentar agregar oficina, 
                regresamos a la vista con un mensaje de error y revertimos los registros fallidos, 
                en caso de haber */
                DB::rollBack();
                return redirect()->route('agregaroficina')->withError(__('No fue posible actualizar oficina, intente más tarde.'));
            }
        } else {
            return redirect()->route('listaroficinas')->withError(__('No tiene autorización para editar datos de otras oficinas.'));
        }

        return redirect()->route('listaroficinas')->withSuccess(__("Oficina actualizada con éxito"));
    }

    // Eliminamos oficina
    public function eliminarOficina(Request $request)
    {

        $auth_user = AuthHelper::authSession();

        $id = json_decode($request->getContent());

        /*Creamos variables desde front mediante petición fetch*/
        $status = 200;
        $mensaje = 'OK';

        $fechasUTC = $this->obtenerHorarioUTC($id);

        $fechaTiempoActualUTC = $fechasUTC[0];
        $fechaActual = $fechasUTC[1];
        $tiempoActual = $fechasUTC[2];

        $oficina = DB::table('ccls as t1')
        ->where('t1.id', $id)
        ->where('t1.status', 1)
        ->get();

        if(count($oficina) > 0 ){

            try{

                // Comenzamos transacción
                DB::beginTransaction();

                // Consultamos citas activas en oficina
                $citas = CitasRegistrada::where('id_ccls', $id)
                ->where('status', 1)
                ->where('status_conciliador', '=', '2')
                ->where('cita_fecha', '>=', $fechaActual)
                ->get();

                // Consultamos usuarios en oficina
                $usuarios = User::where('id_ccls', $id)
                ->where('status', '=', '1')
                ->get();

                $correoCcl = User::where('id_ccls', $id)
                ->where('perfil', '=', 'administrador')
                ->where('status', '=', '1')
                ->first();

                // Consultamos configuracióon de oficina
                $config = CitasConfiguracionOficinas::where('id_ccls', $id)->get();

                // Consultamos fechas configuradas de oficina
                $fechas = CitasDisponibilidadFechasOficinas::where('id_ccls', $id)
                ->where('status', 1)
                ->get();

                // Consultamos horario configurado en oficina
                $horarios = CitasDisponibilidadOficinas::where('id_ccls', $id)
                ->where('status', 1)
                ->get();                

                // Si hay citas activas, cancelamos todas las citas de la oficina
                if(count($citas) > 0){

                    CitasRegistrada::where('id_ccls', $id)
                    ->where('status', 1)
                    ->where('status_conciliador', '=', '2')
                    ->update([
                        'status' => 0, 
                        'status_conciliador' => '0', 
                        'observaciones_conciliador' => 'La oficina fue dada de baja, lamentamos las molestias que podamos causar.', 
                        'updated_at' => $fechaTiempoActualUTC
                    ]);

                }


                // Si hay usuarios, eliminamos a todos los uuarios de la oficina
                if(count($usuarios) > 0){

                    User::where('id_ccls', $id)
                    ->where('perfil', '!=', 'superusuario')
                    ->where('status', '=', '1')
                    ->update([
                        'status' => '0',
                        'updated_at' => $fechaTiempoActualUTC
                    ]);

                }

                // Si existe configuración, actualizamos última modificación
                if(count($config) > 0){

                    CitasConfiguracionOficinas::where('id_ccls', $id)
                    ->where('status', 1)
                    ->update([
                        'status' => 0,
                        'updated_at' => $fechaTiempoActualUTC
                    ]);

                }

                // Si hay fechas disponibles en oficina, cancelamos
                if(count($fechas) > 0){

                    CitasDisponibilidadFechasOficinas::where('id_ccls', $id)
                    ->where('status', 1)
                    ->update([
                        'status' => 0, 
                        'updated_at' => $fechaTiempoActualUTC
                    ]);

                }

                // Si hay horarios disponibles en oficina, cancelamos
                if(count($horarios) > 0){

                    CitasDisponibilidadOficinas::where('id_ccls', $id)
                    ->where('status', 1)
                    ->update([
                        'status' => 0, 
                        'updated_at' => $fechaTiempoActualUTC
                    ]);

                }

                // Eliminamos oficina
                DB::table('ccls as t1')
                ->where('t1.id', $id)
                ->update([
                    't1.status' => 0, 
                    'updated_at' => $fechaTiempoActualUTC
                ]);

                
                //Si logramos eliminar en la DB y todo está bien, enviamos correos todos los usuarios
                DB::afterCommit(function () use($citas, $usuarios, $correoCcl){
                    /*Enviamos el correo informando de oficina eliminada*/

                    if(count($citas) > 0){
                        foreach ($citas as $cita) {
                            try{
                                Mail::to($cita->correo)->bcc($correoCcl->buzon ? $correoCcl->buzon : $correoCcl->email)->send(new NotificacionEliminarCCLS($cita));
                            }catch(Exception $e) {                                    
                                Mail::to($correoCcl->buzon ? $correoCcl->buzon : $correoCcl->email)->send(new NotificacionEliminarCCLS($cita, True));
                            }
                            
                        }
                    }

                    if(count($usuarios) > 0){
                        foreach ($usuarios as $usuario) {
                            try{
                                Mail::to($usuario->email)->bcc($correoCcl->buzon ? $correoCcl->buzon : $correoCcl->email)->send(new NotificacionEliminarCCLSUsuarios($usuario));
                            }catch(Exception $e) {                                    
                                Mail::to($correoCcl->buzon ? $correoCcl->buzon : $correoCcl->email)->send(new NotificacionEliminarCCLSUsuarios($usuario, True));
                            }                                
                        }
                    }

                });
            
                 
                //Terminamos la transacción
                DB::commit();

            }catch(\Exception $e){
                /* En caso de error al intentar eliminar oficina, 
                regresamos a la vista con un mensaje de error y revertimos los registros fallidos, 
                en caso de haber */
                DB::rollBack();
                /* dd($e); */
                $status = 500;
                $mensaje = 'FAIL';
                return redirect()->route('dashboard')->withError(__('No fue posible eliminar oficina, intente más tarde.'));
            }

        }else{

            $status = 404;
            $mensaje = 'NOT FOUND';

        }

        $response = [
            'status' => $status,
            'message' => $mensaje,
        ];
      
        return response()->json($response);
    }

    // Obtener historial de configuraciones guardadas
    public function historialConfig()
    {

        $auth_user = AuthHelper::authSession();
        
        // Obtenemos la zona horaria
        $fechasUTC = $this->obtenerHorarioUTC($auth_user->id_ccls);

        $fechaTiempoActualUTC = $fechasUTC[0];
        $fechaActual = $fechasUTC[1];
        $tiempoActual = $fechasUTC[2];

        // Construcción de la subconsulta para obtener las configuriaciones únicas
        $subquery = DB::table('citas_registradas')
        ->select('id_configuracion', DB::raw('MAX(cita_fecha) AS cita_Fecha'))
        ->where('id_ccls', $auth_user->id_ccls)
        ->where('status', 1)
        ->where('status_conciliador', '2')
        ->groupBy('id_configuracion');

        // Consulta principal para unir con la configuración y las citas
        $configuraciones = DB::table('citas_configuracion_oficinas AS t1')
            ->leftJoinSub($subquery, 'x', function($join) {
                $join->on('t1.id', '=', 'x.id_configuracion');
            })
            ->where('t1.id_ccls', $auth_user->id_ccls)
            ->where('t1.status', 1)
            ->where('t1.id_administrador', $auth_user->id)
            ->select('t1.*', 'x.id_configuracion', 'x.cita_Fecha')
            ->orderBy('t1.aplica', 'desc')
            ->get();
        
        return view('public.oficinas.listar-configuraciones', compact('configuraciones', 'fechaActual'));

    }

    // Eliminamos configuración de oficina
    public function eliminarConfiguracion(Request $request)
    {

        $auth_user = AuthHelper::authSession();

        $id = json_decode($request->getContent());

        $fechasUTC = $this->obtenerHorarioUTC($auth_user->id_ccls);

        $fechaTiempoActualUTC = $fechasUTC[0];
        $fechaActual = $fechasUTC[1];
        $tiempoActual = $fechasUTC[2];

        /*Creamos variables desde front mediante petición fetch*/
        $status = 200;
        $mensaje = 'OK';

        $config = CitasConfiguracionOficinas::where('id_ccls', $auth_user->id_ccls)
            ->where('id_administrador', $auth_user->id)
            ->where('id', $id)
            ->first();

        // Si está todo bien
        if(isset($config) > 0){

            try{

            // Comenzamos transacción
            DB::beginTransaction();

            // Borrado lógico de horarios
            CitasDisponibilidadOficinas::where('id_ccls', $auth_user->id_ccls)
            ->where('id_administrador', $auth_user->id)
            ->where('status', 1)
            ->where('aplica', $config->aplica)
            ->update([
                'status' => 0, 
                'updated_at' => $fechaTiempoActualUTC
            ]);
            
            // Borrado lógico configuración
            CitasConfiguracionOficinas::where('id', $id)
            ->where('id_ccls', $auth_user->id_ccls)
            ->where('id_administrador', $auth_user->id)
            ->where('status', 1)
            ->update([
                'status' => 0, 
                'updated_at' => $fechaTiempoActualUTC
            ]);

            //Terminamos la transacción
            DB::commit();

            }catch(\Exception $e){
                /* En caso de error al intentar eliminar oficina, 
                regresamos a la vista con un mensaje de error y revertimos los registros fallidos, 
                en caso de haber */
                DB::rollBack();
                $status = 500;
                $mensaje = 'FAIL';
                return redirect()->route('historial')->withError(__('No fue posible eliminar oficina, intente más tarde.'));
            }
        }else{

            $status = 500;
            $mensaje = 'NOT FOUND';
        }

        $response = [
            'status' => $status,
            'message' => $mensaje,
        ];
      
        return response()->json($response);
    }

    /*Función de apoyo para obtener los horarios utc por oficina */
    public function obtenerHorarioUTC($id_ccls){

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