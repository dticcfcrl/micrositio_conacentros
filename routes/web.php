<?php

// Controllers
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\VacacionesController;
use App\Http\Controllers\CitasController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\Calculator;
use App\Http\Controllers\BotManController;
use App\Http\Controllers\Appointments;
use App\Http\Controllers\OficinaController;
use App\Http\Controllers\Auth\PasswordResetLinkController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

require __DIR__.'/auth.php';

Route::get('/storage', function () {
    Artisan::call('storage:link');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::controller(CitasController::class)->group(function(){
    Route::get('/ccls/citacancelada/{folio}', 'citaCanceladaCcls')->name('citacanceladacclspublic');
    Route::get('/ccls/{entidadSeleccionada}/oficinas', 'agendarCitaCcls')->name('agendarcitacclspublic');
    Route::get('/ccls/{oficinasSelecionada}', 'disponibilidadFechasCcls')->name('disponibilidafechasccls');
    Route::get('/ccls/{oficinasSelecionada}/{fechaSelecionada}', 'disponibilidadCitaCcls')->name('disponibilidadcitacclspublic');
    Route::post('/agendar-cita/cita-registrada', 'guardarCitaRegistrada')->name('guardarcitaregistradapublic');
    Route::post('/agendar-cita/renviar-correo', 'reenviarCorreo')->name('reenviarcorreopublic');
    /* Route::post('/crear/nuevo/folio', 'obtenerFolio')->name('obtenerfolio'); */
});

Route::middleware('auth')->group(function () {
    // hacer pruebas de usuarios
    /* Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy'); */

    // Dashboard Routes
    Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');
    // Cancelar citas del día
    Route::get('/cancelar', [CitasController::class, 'cancelarCitasDia'])->middleware('can:disponibilidad_fechas_oficina')->name('cancelarcitasdia');

    // Disponibilidad oficinas
    Route::get('oficinas', [OficinaController::class, 'listarOficinas'])->middleware('can:listar_oficinas')->name('listaroficinas');
    Route::get('oficinas/crear', [OficinaController::class, 'agregarOficina'])->middleware('can:agregar_oficina')->name('agregaroficina');
    Route::delete('oficina/eliminar', [OficinaController::class, 'eliminarOficina'])->middleware('can:eliminar_oficina')->name('eliminaroficina');
    Route::post('oficina/guardar', [OficinaController::class, 'guardarOficina'])->middleware('can:agregar_oficina')->name('guardaroficina');
    Route::get('oficinas/{id}/editar', [OficinaController::class, 'editarOficina'])->middleware('can:editar_oficina')->name('editaroficina');
    Route::put('oficina/actualizar', [OficinaController::class, 'actualizarOficina'])->middleware('can:editar_oficina')->name('actualizaroficina');
    Route::get('/ccls/oficinas/{entidadSeleccionada}/municipios', [OficinaController::class, 'entidadMunicipio'])->name('entidadmunicipio');
    Route::get('/configuraciones', [OficinaController::class, 'historialConfig'])->middleware('can:configuracion')->name('listarconfig');
    Route::delete('configuracion/{id}/eliminar', [OficinaController::class, 'eliminarConfiguracion'])->middleware('can:configuracion')->name('eliminarconfiguracion');

    //disponibilidad de fechas
    Route::get('/fechas', [CitasController::class, 'configurarDisponibilidadFechasOficina'])->middleware('can:disponibilidad_fechas_oficina')->name('disponibilidadfechas');
    Route::post('/validar/citas', [CitasController::class, 'validarFechasAgendadas'])->middleware('can:disponibilidad_fechas_oficina')->name('validarfechasagendadas');
    Route::post('/fechas', [CitasController::class, 'guardarDisponiblesFechasOficina'])->middleware('can:disponibilidad_fechas_oficina')->name('guardardisponibilidadfechas');

    //disponibilidad de horarios y configuración
    Route::get('/configuracion', [CitasController::class, 'configurarDisponibilidadOficina'])->middleware('can:configuracion')->name('disponibilidad');
    Route::post('/configuracion/validar/citas', [CitasController::class, 'validaConfiguracion'])->middleware('can:configuracion')->name('validaconfiguracion');
    Route::post('/configuracion/dias', [CitasController::class, 'validarDiasConfiguracion'])->middleware('can:configuracion')->name('validardiasconfiguracion');
    Route::post('/configuracion/usuarios', [CitasController::class, 'validarUsuariosConfiguracion'])->middleware('can:configuracion')->name('validarusuariosconfiguracion');
    Route::post('/configuracion/horarios', [CitasController::class, 'validarHorariosConfiguracion'])->middleware('can:configuracion')->name('validarhorariosconfiguracion');
    Route::post('/configuracion', [CitasController::class, 'guardarHorasDisponiblesOficina'])->middleware('can:configuracion')->name('guardardisponibilidad');

    //configurar vacaciones
    Route::get('/vacaciones', [VacacionesController::class, 'configurarVacaciones'])->middleware('can:configuracion')->name('configurarvacaciones');
    Route::get('/vacaciones/{id}', [VacacionesController::class, 'usuarioVacaciones'])->middleware('can:configuracion')->name('usuariovacaciones');
    Route::post('/fechas-vacaciones', [VacacionesController::class, 'guardarVacacionesFechas'])->middleware('can:configuracion')->name('guardarvacacionesfechas');

    // Cancelar citas del día
    Route::post('/cancelar/motivo', [CitasController::class, 'cancelarCitasDia'])->middleware('can:disponibilidad_fechas_oficina')->name('cancelarcitasdia');

    //vista de citas registradas y acciones de cancelación y confirmación de cita por parte del conciliador
    Route::get('/atencion-citas', [CitasController::class, 'atencionCitasConciliador'])->middleware('can:atencion_citas')->name('atencioncitas');
    /* Route::get('/cita-antendida-conciliador/{folio}', [CitasController::class, 'citaAtendidaConciliador'])->name('citaatentidaconciliador'); Dejo de usarse está función */
    Route::post('cita-cancelada-conciliador', [CitasController::class, 'accionCitaConciliador'])->name('accioncitaconciliador');

    Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');
    // Users Module
    Route::resource('usuarios', UserController::class)->names('users');
});

//App Details Page => 'Dashboard'], function() {
Route::group(['prefix' => 'menu-style'], function() {
    //MenuStyle Page Routs
    Route::get('horizontal', [HomeController::class, 'horizontal'])->name('menu-style.horizontal');
    Route::get('dual-horizontal', [HomeController::class, 'dualhorizontal'])->name('menu-style.dualhorizontal');
    Route::get('dual-compact', [HomeController::class, 'dualcompact'])->name('menu-style.dualcompact');
    Route::get('boxed', [HomeController::class, 'boxed'])->name('menu-style.boxed');
    Route::get('boxed-fancy', [HomeController::class, 'boxedfancy'])->name('menu-style.boxedfancy');
});

//App Details Page => 'special-pages'], function() {
Route::group(['prefix' => 'special-pages'], function() {
    //Example Page Routs
    Route::get('billing', [HomeController::class, 'billing'])->name('special-pages.billing');
    Route::get('calender', [HomeController::class, 'calender'])->name('special-pages.calender');
    Route::get('kanban', [HomeController::class, 'kanban'])->name('special-pages.kanban');
    Route::get('pricing', [HomeController::class, 'pricing'])->name('special-pages.pricing');
    Route::get('rtl-support', [HomeController::class, 'rtlsupport'])->name('special-pages.rtlsupport');
    Route::get('timeline', [HomeController::class, 'timeline'])->name('special-pages.timeline');
});
    
//Widget Routs
Route::group(['prefix' => 'widget'], function() {
    Route::get('widget-basic', [HomeController::class, 'widgetbasic'])->name('widget.widgetbasic');
    Route::get('widget-chart', [HomeController::class, 'widgetchart'])->name('widget.widgetchart');
    Route::get('widget-card', [HomeController::class, 'widgetcard'])->name('widget.widgetcard');
});

//Maps Routs
Route::group(['prefix' => 'maps'], function() {
    Route::get('google', [HomeController::class, 'google'])->name('maps.google');
    Route::get('vector', [HomeController::class, 'vector'])->name('maps.vector');
});

//Auth pages Routs
Route::group(['prefix' => 'auth'], function() {
    Route::get('signin', [HomeController::class, 'signin'])->name('auth.signin');
    Route::get('signup', [HomeController::class, 'signup'])->name('auth.signup');
    Route::get('confirmmail', [HomeController::class, 'confirmmail'])->name('auth.confirmmail');
    Route::get('lockscreen', [HomeController::class, 'lockscreen'])->name('auth.lockscreen');
    Route::get('recoverpw', [HomeController::class, 'recoverpw'])->name('auth.recoverpw');
    Route::get('userprivacysetting', [HomeController::class, 'userprivacysetting'])->name('auth.userprivacysetting');
});

//Error Page Route
Route::group(['prefix' => 'errors'], function() {
    Route::get('error404', [HomeController::class, 'error404'])->name('errors.error404');
    Route::get('error500', [HomeController::class, 'error500'])->name('errors.error500');
    Route::get('maintenance', [HomeController::class, 'maintenance'])->name('errors.maintenance');
});


//Forms Pages Routs
Route::group(['prefix' => 'forms'], function() {
    Route::get('element', [HomeController::class, 'element'])->name('forms.element');
    Route::get('wizard', [HomeController::class, 'wizard'])->name('forms.wizard');
    Route::get('validation', [HomeController::class, 'validation'])->name('forms.validation');
});

//Table Page Routs
Route::group(['prefix' => 'table'], function() {
    Route::get('bootstraptable', [HomeController::class, 'bootstraptable'])->name('table.bootstraptable');
    Route::get('datatable', [HomeController::class, 'datatable'])->name('table.datatable');
});

//Icons Page Routs
Route::group(['prefix' => 'icons'], function() {
    Route::get('solid', [HomeController::class, 'solid'])->name('icons.solid');
    Route::get('outline', [HomeController::class, 'outline'])->name('icons.outline');
    Route::get('dualtone', [HomeController::class, 'dualtone'])->name('icons.dualtone');
    Route::get('colored', [HomeController::class, 'colored'])->name('icons.colored');
});

    Route::post('/resultado-calcular-prestaciones', [Calculator::class, 'resultadoCalculoPrestaciones'])->name('calculo');
    Route::post('/resultado-pdf', [Calculator::class, 'obtenerPDF'])->name('calculo-pdf');
    Route::match(['get','post'],'/botman',[BotManController::class,'handle']);

//Auth pages Routs

Route::group(['prefix' => 'auth'], function() {
    Route::get('signin', [HomeController::class, 'signin'])->name('auth.signin');
    Route::get('signup', [HomeController::class, 'signup'])->name('auth.signup');
    Route::get('confirmmail', [HomeController::class, 'confirmmail'])->name('auth.confirmmail');
    Route::get('lockscreen', [HomeController::class, 'lockscreen'])->name('auth.lockscreen');
    Route::get('recoverpw', [HomeController::class, 'recoverpw'])->name('auth.recoverpw');
    Route::get('userprivacysetting', [HomeController::class, 'userprivacysetting'])->name('auth.userprivacysetting');
});

    Route::post('/agendar-cita', [Appointments::class, 'guardarCitaRegistrada'])->name('generarCita');

    //Extra Page Routs
Route::get('privacy-policy', [HomeController::class, 'privacypolicy'])->name('pages.privacy-policy');
Route::get('terms-of-use', [HomeController::class, 'termsofuse'])->name('pages.term-of-use');
Route::match(['get','post'],'/botman',[BotManController::class,'handle']);

// mapa para identificar el CCL dado el sector, subsector, rama y subrama
Route::get('/localiza-tu-ccl-info', [HomeController::class, 'localizaTuCCLInfo'])->name('localizatucclinfopublic');
Route::get('/localiza-tu-ccl', [HomeController::class, 'localizaTuCCL'])->name('localizatucclpublic');
Route::get('/localiza-tu-ccl-ambito/{ambito}', [HomeController::class, 'localizaTuCCLAmbito'])->name('localizatucclambitopublic');

Route::statamic('search', 'search');