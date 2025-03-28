<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;


class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     *
     * @param  \App\Http\Requests\Auth\LoginRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(LoginRequest $request)
    {
        $user = User::where('email', $request->email)
        ->where('status', '=', '1')
        ->first();
        if(!empty($user)){
            $fechaExpiracionPassd = Carbon::parse($user->password_changed_at);
            $hoy = Carbon::now();   
            $acceso = $fechaExpiracionPassd->gte($hoy);
            if(!$acceso){
                return redirect('/login')->withErrors(['email' => __('Tu contraseña caducó. Favor de solicitad liga de cambio de contraseña con el administrador.')]);
            }
        }
        
        // Usuario eliminado
        if(empty($user)){
            return back()->withInput($request->only('email'))->withErrors(['email' => __('Usuario sin resultados.')]);
        }
        
        $request->authenticate();

        $request->session()->regenerate();

        return redirect(RouteServiceProvider::HOME);
    }

    /**
     * Destroy an authenticated session.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
