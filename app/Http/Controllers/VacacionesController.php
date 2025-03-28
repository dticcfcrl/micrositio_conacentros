<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\AuthHelper;
use App\Models\CitasConfiguracionOficinas;
use App\Models\CitasDisponibilidadFechasOficinas;
use App\Models\CitasRegistrada;
use App\Models\DisponibilidadOficinas;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\NotificacionEliminarCCLS;
use App\Mail\NotificacionEliminarCCLSUsuarios;
use App\Models\CitasConfigurarVacaciones;

class VacacionesController extends Controller
{
    //Controles para vacaciones

    //Listar oficinas
    public function configurarVacaciones()
    {

        $auth_user = AuthHelper::authSession();

        //Obtenemmos los usuarios
        $usuarios = User::where('id_ccls', $auth_user->id_ccls)
        ->where('status', '1')
        ->where('perfil', '!=', 'administrador')
        ->where('perfil', '!=', 'superusuario')
        ->get();

        //Obtenemos las fechas de vacaciones
        $fechasVacaciones = CitasConfigurarVacaciones::where('id_responsable', $auth_user->id)->get();
        
        return view('public.vacaciones.configurar-vacaciones', compact('usuarios','fechasVacaciones'));

    }

    //Obtenemos las vacaciones del usario
    public function usuarioVacaciones($id)
    {

        $auth_user = AuthHelper::authSession();

        /*Creamos variables para envio al front mediante petición fetch*/
        $status = 200;
        $mensaje = 'OK';

        // Obtenemos las vacaciones
        $vacaciones = CitasConfigurarVacaciones::where('id_responsable', $auth_user->id)
        ->where('id_usuario', $id)
        ->where('status', 1)
        ->get();

        // regresamos los datos
        $response = [
            'status' => $status,
            'message' => $mensaje,
            'data' => $vacaciones
        ];

        return response()->json($response);
    }

    //Guardamos las fechas de vacaciones
    public function guardarVacacionesFechas(Request $request){

        $auth_user = AuthHelper::authSession();

        /*Creamos variables para envio al front mediante petición fetch*/
        $status = 200;
        $mensaje = 'OK';

        $fechasVacaciones = json_decode($request->getContent());

        $fechasUTC = $this->obtenerHorarioUTC($auth_user->id_ccls);

        $fechaTiempoActualUTC = $fechasUTC[0];
        $fechaActual = $fechasUTC[1];
        $tiempoActual = $fechasUTC[0]; 

        try{
            
            //Comenzamos transacción
            DB::beginTransaction();

            foreach($fechasVacaciones->diasSelecionados as $disponible){

                if($disponible != $fechaActual){

                    //Obtenemos datos de fechas por oficina
                    $existeFecha = CitasConfigurarVacaciones::where('id_responsable', $auth_user->id)
                    ->where('id_usuario', $fechasVacaciones->usuario)
                    ->where('fecha', $disponible)
                    ->get()->pluck('status');        
                    
                    /*Preguntaos si la fecha existe*/
                    if(count($existeFecha) > 0){

                        $invertirStatus = $existeFecha[0]; //Variable auxiliar para invertir status
                        /*Si existe, actualizamos las fechas dado que el administrador quiere seleccionar fechas que no desea*/
                        CitasConfigurarVacaciones::where('id_responsable', $auth_user->id)
                        ->where('id_usuario', $fechasVacaciones->usuario)
                        ->where('fecha', $disponible)
                        ->update([
                            'status' => !$invertirStatus,
                            'updated_at' => $fechaTiempoActualUTC
                        ]);

                    }else{

                        /*Si no existe, guardamos las fechas dado que el administrador quiere nuevas fechas deseadas*/
                        CitasConfigurarVacaciones::create([
                            'id_responsable' => $auth_user->id,
                            'id_usuario' => $fechasVacaciones->usuario,
                            'fecha' => $disponible,
                            'status' => true,
                            'created_at' => $fechaTiempoActualUTC,
                            'updated_at' => $fechaTiempoActualUTC
                        ]);

                    }

                }

            }

            //Terminamos la transacción
            DB::commit();
            
        }catch(\Exception $e){
            DB::rollBack();
            $status = 500;
            $mensaje = 'FAIL';
        }

        // regresamos los datos
        $response = [
            'status' => $status,
            'message' => $mensaje
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