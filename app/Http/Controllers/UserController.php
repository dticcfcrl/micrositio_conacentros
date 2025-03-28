<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Auth;
use Illuminate\Http\Request;
use App\DataTables\UsersDataTable;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Helpers\AuthHelper;
use Spatie\Permission\Models\Role;
use App\Http\Requests\UserRequest;
use App\Models\CitasRegistrada;
use App\Models\CitasConfiguracionOficinas;
use Illuminate\Support\Str;
use App\Models\DisponibilidadOficinas;
use Illuminate\Support\Arr;
use Mockery\Undefined;
use Carbon\Carbon;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Log;
use App\Mail\PruebaCorreoBuzon;
use Illuminate\Support\Facades\Mail;
use Exception;

class UserController extends Controller

{

    public function __construct()
    {
        $this->middleware('can:users')->only('index');
        $this->middleware('can:users')->only('create');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(UsersDataTable $dataTable)
    {
        $pageTitle = trans('global-message.list_form_title',['form' => trans('users.title')] );
        $auth_user = AuthHelper::authSession();
        $assets = ['data-table'];
        $headerAction = '<a href="'.route('users.create').'" class="btn btn-sm btn-primary btn-primary-ccls" role="button" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Crear usuario">
        <svg width="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd" clip-rule="evenodd" d="M9.5 12.5537C12.2546 12.5537 14.4626 10.3171 14.4626 7.52684C14.4626 4.73663 12.2546 2.5 9.5 2.5C6.74543 2.5 4.53737 4.73663 4.53737 7.52684C4.53737 10.3171 6.74543 12.5537 9.5 12.5537ZM9.5 15.0152C5.45422 15.0152 2 15.6621 2 18.2464C2 20.8298 5.4332 21.5 9.5 21.5C13.5448 21.5 17 20.8531 17 18.2687C17 15.6844 13.5668 15.0152 9.5 15.0152ZM19.8979 9.58786H21.101C21.5962 9.58786 22 9.99731 22 10.4995C22 11.0016 21.5962 11.4111 21.101 11.4111H19.8979V12.5884C19.8979 13.0906 19.4952 13.5 18.999 13.5C18.5038 13.5 18.1 13.0906 18.1 12.5884V11.4111H16.899C16.4027 11.4111 16 11.0016 16 10.4995C16 9.99731 16.4027 9.58786 16.899 9.58786H18.1V8.41162C18.1 7.90945 18.5038 7.5 18.999 7.5C19.4952 7.5 19.8979 7.90945 19.8979 8.41162V9.58786Z" fill="currentColor"></path>
        </svg>
        </a>';

        //obtenemos todos los usuarios por administrador/oficina
        if($auth_user->perfil != 'superusuario'){
            $listar_usuarios = DB::table('users as t1')
            ->select('t1.*', 't2.abreviacion', 't3.municipio', 't3.ambito')
            ->join('estados as t2', 't1.id_estado', '=', 't2.clave')
            ->join('ccls as t3', 't1.id_ccls', '=', 't3.id')
            ->where('id_ccls', '=', $auth_user->id_ccls)
            ->where('t1.status', '1')
            ->get();
        
        }else{
            //obtenemos todos los usuarios por superusaurio
            $listar_usuarios = DB::table('users as t1')
            ->select('t1.*', 't2.abreviacion', 't3.municipio', 't3.ambito')
            ->join('estados as t2', 't1.id_estado', '=', 't2.clave')
            ->join('ccls as t3', 't1.id_ccls', '=', 't3.id')
            ->where('t1.status', '1')            
            ->get();
        }
        
        return $dataTable->render('global.datatable', compact('pageTitle','auth_user','assets', 'headerAction', 'listar_usuarios'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $auth_user = AuthHelper::authSession();

        /*Obtenemos datos de estado del usuario*/
        $estado_nombre = DB::table('estados as t1')->where('clave', '=', $auth_user->id_estado)->get();
        
        if($auth_user->perfil === 'superusuario'){
            $roles = Role::where('status', 1)
            ->whereIn('name', ['administrador', 'conciliador', 'auxiliar'])
            ->get()->pluck('name', 'id');
            $estados = DB::table('estados as t1')->get()->pluck('nombre', 'clave');
        }else{
            $roles = Role::whereIn('name', ['conciliador', 'auxiliar'] )->get()->pluck('name', 'id');
            $estados = DB::table('estados as t1')->where('clave', $auth_user->id_estado)->get()->pluck('nombre', 'clave'); 
        }        

        return view('users.form', compact('roles', 'estados', 'estado_nombre'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
    {
        
        $auth_user = AuthHelper::authSession();        

        $usuarioPerfil = ''; //variable auxiliar

        //Obtenemos la hora UTC de la oficina
        $fechasUTC = $this->obtenerHorarioUTC($request->oficina);

        $fechaTiempoActualUTC = $fechasUTC[0];
        $fechaActual = $fechasUTC[1];
        $tiempoActual = $fechasUTC[2];

        //Establecer la fecha de expiracion de la contraseña 
        $fechaExpPssd =  Carbon::now()->addYear(); // a la fecha de creación se le agrega un año

        /*Validamos los campos*/
        $request->validate([
            'nombre' =>'required|max:50',
            'apellidos' =>'required|max:50',
            'email' =>'required|max:50',
            'password' =>'required',
            'celular' =>'required|max:10',
            'estado' =>'required',
            'oficina' =>'required',
            'perfil' =>'required',
        ]);

        $request['password'] = bcrypt($request->password);

        $request['usuario'] = $request->usuario ?? stristr($request->email, "@", true) . rand(100,1000);

        if($auth_user->perfil == 'superusuario'){
            $estado = DB::table('estados as t1')->select('t1.clave', 't1.nombre')->where('t1.nombre', '=', request('estado'))->get(); 
            $clave = $estado[0]->clave;
        }else{
            $clave = $request['estado'];        
        }
        

        /*Obtenemos el administrador de oficina*/
        $admin = User::where('perfil', request('perfil'))
        ->where('id_ccls', $request['oficina'])
        ->where('perfil', 'administrador')
        ->where('status', '1')
        ->first();

        /*Obtenemos todos los usuarios del perfil por oficina*/ 
        $usuarios = User::where('id_ccls',  $request['oficina'])
        ->where('perfil', request('perfil'))
        ->where('status', '1')
        ->count();
        
                
                
        //Validamos si el usuario no sobre pasa el total configurado por el administrador de oficina
        //También validamos que haya un administrador por oficina
        if($usuarios < 50){

            
            if($request['perfil'] != 'administrador'){

                try{
                    //Comenzamos transacción
                    DB::beginTransaction();
            
                    $user = User::create([
                        'nombre' => Str::lower(request('nombre')),
                        'apellidos' => Str::lower(request('apellidos')),
                        'email' => Str::lower(request('email')),
                        'buzon' => Str::lower(request('buzon')),
                        'password' => bcrypt(request('password')),
                        'no_personal' => request('celular'),
                        'email_verificado' => now(), 
                        'id_estado' => $clave,
                        'id_ccls' => request('oficina'),
                        'perfil' => request('perfil'),
                        'password_changed_at' => $fechaExpPssd,
                        'status' => '1',
                    ]);
                            
                    $user->assignRole($request->perfil);                        
            
                    //Terminamos la transacción
                    DB::commit();
    
                }catch(\Exception $e){
                    /* En caso de error al intentar registrar usuario, 
                    regresamos a la vista con un mensaje de error y revertimos los registros fallidos, 
                    en caso de haber */
                    Log::error($e);
                    DB::rollBack();
                    return redirect()->route('users.index')->withError(__('No fue posible registrar al usuario, email o buzón podrían estar duplicados, intente más tarde.'));
                }
    
                return redirect()->route('users.index')->withSuccess(__('Usuario creado',['name' => __('users.store')]));

            }else{

                /*Validamos que no exista algún administrador en usuarios*/
                if(!isset($admin)){       
                    if($request['perfil'] === 'administrador'){
                        try {
                            Mail::to(Str::lower(request('buzon')))->send(new PruebaCorreoBuzon());
                        } catch(Exception $e) {
                            return redirect()->route('users.create')->withError(__('El correo del buzón no existe o es incorrecto, verifiquelo e intente de nuevo.'));
                        }
                    }         

                    /*Creamos usuarios que no dependan del administrador*/
                    if($request['perfil'] === 'superusuario' || $request['perfil'] === 'administrador'){

                        try{
                            
                            //Comenzamos transacción
                            DB::beginTransaction();
            
                            /* $user = User::create($request->all()); */
            
                            $user = User::create([
                                'nombre' => Str::lower(request('nombre')),
                                'apellidos' => Str::lower(request('apellidos')),
                                /* 'usuario' => Str::lower(request('usuario')), */
                                'email' => Str::lower(request('email')),
                                'buzon' => Str::lower(request('buzon')),
                                'password' => bcrypt(request('password')),
                                'no_personal' => request('celular'),
                                'email_verificado' => now(), 
                                'id_estado' => $clave,
                                'id_ccls' => request('oficina'),
                                'perfil' => request('perfil'),
                                'password_changed_at' => $fechaExpPssd,
                                'status' => '1',
                                'created_at' => $fechaTiempoActualUTC,
                                'updated_at' => $fechaTiempoActualUTC
                            ]);
                                        
                            $user->assignRole($request->perfil);                   
            
                            //Terminamos la transacción
                            DB::commit();
            
                        }catch(Exception $e){
                            /* En caso de error al intentar registrar usuario, 
                            regresamos a la vista con un mensaje de error y revertimos los registros fallidos, 
                            en caso de haber */
                            DB::rollBack();
                            return redirect()->route('users')->withError(__('No fue posible registrar usuario, intente más tarde.'));
                        }
        
                        return redirect()->route('users.index')->withSuccess(__('Usuario creado',['name' => __('users.store')]));
        
                    }else{
                        
                        return redirect()->route('users.create')->withError(__('No fue posible registrar usuario, intente más tarde.'));
        
                    }

                }else{

                    return redirect()->route('users.create')->withError(__('Solo se permite un responsable por oficina.'));

                }
            }
        
        }else{
            return redirect()->route('users.create')->withError(__('Limite de usuarios excedidos.'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = User::with('userProfile','roles')->findOrFail($id);

        $profileImage = getSingleMedia($data, 'profile_image');

        return view('users.profile', compact('data', 'profileImage'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $auth_user = AuthHelper::authSession();

        /*Variable auxiliar que controla el bloqueo de estado, oficina y perfil*/
        $bloqueo = false;
 
        /*Obtenemos los datos de usuario y su rol */
        $usuario_edit = User::with('roles')->findOrFail($id);

        /*En caso de haber tenido cita bloqueamos campos que no pueden editar, si no hay citas registradas, podemos cambir datos, a nivel oficina */
        $citasRegistradas = CitasRegistrada::where('id_ccls', $usuario_edit->id_ccls)->count();

        if($citasRegistradas > 0 && $auth_user->perfil === 'superusuario'){
            $bloqueo = true;
        }else if($auth_user->perfil !== 'superusuario'){
            $bloqueo = true;
        }

        /*Obtenemos datos de estado del usuario*/
        $estado_nombre = DB::table('estados as t1')->where('clave', '=', $usuario_edit->id_estado)->get();

        /*Obtenemos role de usuario */
        if($auth_user->perfil == 'superusuario'){
            $roles = Role::where('status', 1)->get()->pluck('name', 'id');
            /*Obtenemos datos de estados */
            $estados = DB::table('estados as t1')->get()->pluck('nombre', 'clave'); 
        }elseif($auth_user->perfil == 'administrador'){
            $roles = Role::whereIn('name', ['administrador', 'conciliador', 'auxiliar'] )->where('status', 1)->get()->pluck('name', 'id');
            $estados = DB::table('estados as t1')->where('clave', $auth_user->id_estado)->get()->pluck('nombre', 'clave'); 
        }else{
            $roles = Role::whereIn('name', ['conciliador', 'auxiliar'] )->where('status', 1)->get()->pluck('name', 'id');
            $estados = DB::table('estados as t1')->where('clave', $auth_user->id_estado)->get()->pluck('nombre', 'clave'); 
        }

        if($auth_user->perfil == 'superusuario'){
            return view('users.form', compact('usuario_edit', 'id', 'roles', 'estados', 'estado_nombre', 'bloqueo'));
        }else if($auth_user->perfil == 'administrador' && $auth_user->id_ccls == $usuario_edit->id_ccls ){
            return view('users.form', compact('usuario_edit', 'id', 'roles', 'estados', 'estado_nombre', 'bloqueo'));
        }elseif($auth_user->id == $id){
            return view('users.form', compact('usuario_edit', 'id', 'roles', 'estados', 'estado_nombre', 'bloqueo'));
        }else{
            return redirect()->route('dashboard')->withError(__('No tiene autorización para editar datos de otros usuarios.'));
        }
     
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserRequest $request, $id)
    {        
        //Obtenemos la hora UTC de la oficina
        $fechasUTC = $this->obtenerHorarioUTC($request->oficina);

        $fechaTiempoActualUTC = $fechasUTC[0];
        $fechaActual = $fechasUTC[1];
        $tiempoActual = $fechasUTC[2];

        $correo = 0;

        /*Validamos los campos*/
        $request->validate([
            'nombre' =>'required|max:50',
            'apellidos' =>'required|max:50',
            'email' =>'required|max:50',
            'celular' =>'required|max:10',
            'estado' =>'required',
            'oficina' =>'required',
            'perfil' =>'required',
            'buzon' =>'max:50',
        ]);

        /*Obtenemos los datos del estado*/
        $estado = DB::table('estados as t1')->select('t1.clave', 't1.nombre')->where('t1.nombre', '=', request('estado'))->get(); 

        // Obtenemos los datos originales del usuario
        $usuario = User::where('id', $id)->get();

        // Verificamos si hay cambios en correo y buzón y si hay dusplidad en alguno de ellos
        if($request->email != $usuario[0]->email){
            $correo = User::where('email', $request->email)->where('status', '1')->count();
        }

        if($request->buzon != $usuario[0]->buzon && $request['perfil'] === 'administrador'){
            try {
                Mail::to(Str::lower(request('buzon')))->send(new PruebaCorreoBuzon());
            } catch(Exception $e) {
                return redirect()->back()->withError(__('El correo del buzón no existe o es incorrecto, verifiquelo e intente de nuevo.',['name' => __('user.store')]));
            }
        }


        // De existir correo, notificamos al usuario del correo duplicado
        if( $correo > 0 ){
            return redirect()->route('users.index')->withError(__('No fue posible actualizar usuario, intente más tarde.'));
        }

        /*Obtenemos el bloqueo en de tener citas*/
        if($request->bloqueo == true){

            /*Guardamos solo la clave del estado */
            $clave = $request->estado;

        }else{

            /*Guardamos solo la clave del estado */
            $clave = $estado[0]->clave;

        }        

        /*Obtenemos los datos de usuario antes de actualizar*/
        $user = User::with('roles')->findOrFail($id);

        /*Obtenemos los roles del perfil*/
        $role = Role::find($request->perfil);        

        try{
            
            //Comenzamos transacción
            DB::beginTransaction();

             /* $user = User::create($request->all()); */
            User::where('id', $id)
                ->update(['nombre' => Str::lower(request('nombre')),  
                'apellidos' => Str::lower(request('apellidos')),            
                'email' => Str::lower(request('email')),
                'buzon' => Str::lower(request('buzon')),
                'password' => $request->password != null ? bcrypt($request->password) : $user->password,
                'no_personal' => request('celular'),
                'id_estado' => $clave,
                'id_ccls' => request('oficina'),
                'perfil' => request('perfil'),
                'updated_at' => $fechaTiempoActualUTC
            ]);
            
            $user->removeRole($user->perfil);            

            //Si logramos guardar en la DB y todo está bien, finalmente guardamos el nuevo role
            DB::afterCommit(function () use($request, $user){
                /*Cambiamo los roles, eliminamos el anterior y asignamos el nuevo*/                  
                $user->assignRole($request->perfil);
            });


            //Terminamos la transacción
            DB::commit();

        }catch(\Exception $e){
            /* En caso de error al intentar actualizar usuario, 
            regresamos a la vista con un mensaje de error y revertimos los registros fallidos, 
            en caso de haber */
            DB::rollBack();
            return redirect()->route('users.index')->withError(__('No fue posible actualizar usuario, intente más tarde.'));
        }     

        if(auth()->check()){
            return redirect()->back()->withSuccess(__('Usuario actualizado',['name' => __('user.store')]));
        }
        return redirect()->back()->withError(__('Datos actualizados',['name' => __('dashboard')]));

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $auth_user = AuthHelper::authSession();

         // Obtenemos el el horario de oficina
        /*Obtenemos la entidad, municipio y dirección de la oficina */
        $oficinaUTC = DB::table('ccls as t1')->select('t1.zona_horaria')
        ->where('t1.id', $auth_user->id_ccls)->get()->first();        

        // se restan horas por servidor configurado en UTC 00:00 de acuerdo a la configuración guardad de oficina
        if($oficinaUTC->zona_horaria === null){
            $utcCDMX = '-6 hour'; 
        }else{
            $utcCDMX = substr($oficinaUTC->zona_horaria,-3, 2).' hour';
        }
        
        // - NOTA: Este valor ($utcCDMX) debería de ser tomado de datos de oficina

        $fechaTiempoActualUTC = date('Y-m-d H:i:s', strtotime($utcCDMX)); // fecha y hora actual configuración UTC
        $fechaActual = date('Y:m:d', strtotime($fechaTiempoActualUTC)); // fecha actual 
        $tiempoActual = date('H:i:s', strtotime($fechaTiempoActualUTC)); // hora actual

        /*Creamos variables desde front mediante petición fetch*/
        $status = 200;
        $mensaje = 'OK';

        /*Obtenemos los datos de usuario antes de actualizar*/
        $user = User::with('roles')->findOrFail($id);

        /* Válida que solo superusuario y administador puedan eliminar usuarios. */
        if(($auth_user->perfil == 'superusuario' || $auth_user->perfil == 'administrador') && $user->perfil != 'superusuario'){


            // Eliminamos al auxiliar
            if($user->perfil == 'auxiliar' || $user->perfil == 'conciliador'){

                try{

                    //Comenzamos transacción
                    DB::beginTransaction();
        
                     // Actualizamos el status a 0 eliminado lógico
                    User::where('id', $id)
                        ->where('id_ccls', $user->id_ccls)
                        ->update(['status' => 0,
                        'updated_at' => $fechaTiempoActualUTC
                    ]);
        
        
                    //Terminamos la transacción
                    DB::commit();
        
                }catch(\Exception $e){
                    /* En caso de error al intentar actualizar usuario, 
                    regresamos a la vista con un mensaje de error y revertimos los registros fallidos, 
                    en caso de haber */
                    DB::rollBack();
                    $status = 500;
                    $mensaje = 'FAIL DELETE';
                }

                
                $response = [
                    'status' => $status,
                    'message' => $mensaje,
                ];

                // usuario eliminado con éxito
                return response()->json($response);

            }else{

                /*  Solo se permite a un administrador por oficina,
                    por lo tatno se notifica al usuario que solo puede 
                    cambiar datos.
                */

                $status = 202;
                $mensaje = "CAN'T DELETE ";

                $response = [
                    'status' => $status,
                    'message' => $mensaje,
                ];

                // Enviamos la respuesta
                return response()->json($response);
            }


        }

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
