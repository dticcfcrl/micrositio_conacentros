<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\AuthHelper;
use App\Models\CitasDisponibilidadOficinas;
use App\Models\CitasConfiguracionOficinas;
use App\Models\User;
// use Illuminate\Http\Post;
// use Illuminate\Http\Post;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Carbon\Carbon;

class HomeController extends Controller
{

    public function __construct()
    {
        $this->middleware('can:dashboard')->only('index');
    }

    /*
     * Vista de personas trabajadoras
     */
    public function personasTrabajadoras(Request $request)
    {
        $cclsUbicaciones = \DB::table('ccls as t1')
        ->select('t1.id', 't1.estado','t1.municipio', 't1.ambito','t1.direccion', 
        't1.url_google', 't1.lat','t1.long', 't1.contacto', 't1.cp')->get();  
        
        $peridiocidades = \DB::table('calculadora_periodicidad')
            ->select('id', 'nombre', 'dias')->orderBy('dias')->get();
   
        $profesiones = \DB::table('calculadora_profesiones')
            ->select('id', 'profesion')->where('id', ">", 1)
            ->orderBy('id')->get();
        
        return view('public.personas-trabajadoras', compact("cclsUbicaciones", "peridiocidades", "profesiones"));
    }
    public function new(Request $request)
    {
        $cclsUbicaciones = \DB::table('ccls as t1')
        ->select('t1.id', 't1.estado','t1.municipio', 't1.ambito','t1.direccion', 
        't1.url_google', 't1.lat','t1.long', 't1.contacto', 't1.cp')->get();              
        
        return view('public.personas-trabajadoras-new', compact("cclsUbicaciones"));
    }
    public function new1(Request $request)
    {
        $cclsUbicaciones = \DB::table('ccls as t1')
        ->select('t1.id', 't1.estado','t1.municipio', 't1.ambito','t1.direccion', 
        't1.url_google', 't1.lat','t1.long', 't1.contacto', 't1.cp')->get();              
        
        return view('public.personas-empleadoras-new', compact("cclsUbicaciones"));
    }
    public function new2(Request $request)
    {
        $cclsUbicaciones = \DB::table('ccls as t1')
        ->select('t1.id', 't1.estado','t1.municipio', 't1.ambito','t1.direccion', 
        't1.url_google', 't1.lat','t1.long', 't1.contacto', 't1.cp')->get();              
        
        return view('public.proceso-conciliacion-new', compact("cclsUbicaciones"));
    }
    public function new3(Request $request)
    {
        $cclsUbicaciones = \DB::table('ccls as t1')
        ->select('t1.id', 't1.estado','t1.municipio', 't1.ambito','t1.direccion', 
        't1.url_google', 't1.lat','t1.long', 't1.contacto', 't1.cp')->get();              
        
        return view('public.acerca-new', compact("cclsUbicaciones"));
    }
    public function apoyo_despido_injustificado1(Request $request)
    {
        $cclsUbicaciones = \DB::table('ccls as t1')
        ->select('t1.id', 't1.estado','t1.municipio', 't1.ambito','t1.direccion', 
        't1.url_google', 't1.lat','t1.long', 't1.contacto', 't1.cp')->get();              
        
        return view('public.apoyo-despido-injustificado1', compact("cclsUbicaciones"));
    }
    public function calcular_cuanto_pagar1(Request $request)
    {
        $cclsUbicaciones = \DB::table('ccls as t1')
        ->select('t1.id', 't1.estado','t1.municipio', 't1.ambito','t1.direccion', 
        't1.url_google', 't1.lat','t1.long', 't1.contacto', 't1.cp')->get();              
        
        return view('public.calcular-cuanto-pagar1', compact("cclsUbicaciones"));
    }
    public function calcular_prestaciones1(Request $request)
    {
        $cclsUbicaciones = \DB::table('ccls as t1')
        ->select('t1.id', 't1.estado','t1.municipio', 't1.ambito','t1.direccion', 
        't1.url_google', 't1.lat','t1.long', 't1.contacto', 't1.cp')->get();              
        
        return view('public.calcular-prestaciones1', compact("cclsUbicaciones"));
    }
    public function despido_injustificado_cont1(Request $request)
    {
        $cclsUbicaciones = \DB::table('ccls as t1')
        ->select('t1.id', 't1.estado','t1.municipio', 't1.ambito','t1.direccion', 
        't1.url_google', 't1.lat','t1.long', 't1.contacto', 't1.cp')->get();              
        
        return view('public.despido-injustificado-cont1', compact("cclsUbicaciones"));
    }

    public function despido_justificado_injustificado1(Request $request)
    {
        $cclsUbicaciones = \DB::table('ccls as t1')
        ->select('t1.id', 't1.estado','t1.municipio', 't1.ambito','t1.direccion', 
        't1.url_google', 't1.lat','t1.long', 't1.contacto', 't1.cp')->get();              
        
        return view('public.despido-justificado-injustificado1', compact("cclsUbicaciones"));
    }
    public function calculadora(Request $request)
    {
        $cclsUbicaciones = \DB::table('ccls as t1')
        ->select('t1.id', 't1.estado','t1.municipio', 't1.ambito','t1.direccion', 
        't1.url_google', 't1.lat','t1.long', 't1.contacto', 't1.cp')->get();              
        
        return view('public.calculadora', compact("cclsUbicaciones"));
    }

    public function home(Request $request)
    {
        $cclsUbicaciones = \DB::table('ccls as t1')
        ->select('t1.id', 't1.estado','t1.municipio', 't1.ambito','t1.direccion', 
        't1.url_google', 't1.lat','t1.long', 't1.contacto', 't1.cp')->get();              
        
        return view('public.home', compact("cclsUbicaciones"));
    }
    /*
     * vista de personas empleadoras
     */
    public function personasEmpleadoras(Request $request)
    {
        return view('public.personas-empleadoras');
    }

     /*
     * Vista de personas conciliadroas
     */
    public function personasConciliadoras(Request $request)
    {
        return view('public.personas-conciliadoras');
    }

    

     /**
     * Vista de mis  derechos como empleado
     */
    public function misDerechosEmpleado(Request $request)
    {
        return view('public.mis-derechos-empleado');
    }

     /**
     * Vista de terminancion laboral justificada
     */
    public function terminacionLaboralJustificada(Request $request)
    {
        return view('public.terminacion-laboral-justificada');
    }


     /**
     * Vista de despido injustificada
     */
    public function despidoInjustificado(Request $request)
    {
        return view('public.despido-injustificado');
    }

     /**
     * Vista de despido injustificada continuidad
     */
    public function despidoInjustificadoCont(Request $request)
    {
        return view('public.despido-injustificado-cont');
    }

    /**
     * Vista de apoyo despido injustificada 
     */
    public function apoyoDespidoInjustificado(Request $request)
    {
        return view('public.apoyo-despido-injustificado');
    }

     /**
     * Vista de apoyo despido injustificada 
     */
    public function calcularPrestaciones(Request $request)
    {
        $peridiocidades = \DB::table('calculadora_periodicidad')
            ->select('id', 'nombre')->orderBy('dias')->get();
   
        $profesiones = \DB::table('calculadora_profesiones')
            ->select('id', 'profesion')->where('id', ">", 1)
            ->orderBy('id')->get();

        return view('public.calcular-prestaciones', compact("peridiocidades", "profesiones", "request"));
    }

    public function calcularPrestaciones2(Request $request)
    {
        $peridiocidades = \DB::table('calculadora_periodicidad')
            ->select('id', 'nombre', 'dias')->orderBy('dias')->get();
   
        $profesiones = \DB::table('calculadora_profesiones')
            ->select('id', 'profesion')->where('id', ">", 1)
            ->orderBy('id')->get();

        return view('public.calcular-prestaciones-2', compact("peridiocidades", "profesiones", "request"));
    }

    public function moneyFormat($amount)
    {
        return '$' . number_format($amount, 2);
    }

      /**
     * Vista de despido injustificada continuidad
     */
    public function despidoJustificadoInjustificado(Request $request)
    {
        return view('public.despido-justificado-injustificado');
    }

     /**
     * Vista de terminancion laboral por mutuo acuerdo
     */
    public function terminacionLaboralMutuoAcuerdo(Request $request)
    {
        return view('public.terminacion-laboral-mutuo-acuerdo');
    }

     /**
     * Vista de cursos conciliadores
     */
    public function cursosConciliadores(Request $request)
    {
        return view('public.cursos-conciliadores');
    }

    /**
     * Vista de proceso de conciliación
     */
    public function procesoConciliacion(Request $request)
    {
        return view('public.proceso-conciliacion');
    }

    /* Vista de mis beneficiarios de seguridad social
    */
   public function misBeneficiariosSS(Request $request)
   {
       return view('public.mis-beneficiarios-seguro-social');
   }
    /* Vista de riesgo de trabajo
    */
    public function riesgosTrabajo(Request $request)
    {
        return view('public.riesgos-trabajo');
    }
    /* Vista de no pagaron mis prestaciones
    */
    public function noPagaronPrestaciones(Request $request)
    {
        return view('public.no-pagaron-prestaciones');
    }

    /* Vista de renuncia voluntaria
    */
    public function renunciaVoluntaria(Request $request)
    {
        return view('public.renuncia-voluntaria');
    }

    /* Vista de renuncia voluntaria
    */
    public function retencionSalario(Request $request)
    {
        return view('public.retencion-salario');
    }

    /* Vista de salario minimo
    */
    public function salarioMinimo(Request $request)
    {
        return view('public.salario-minimo');
    }

    /* Vista de horas extra
    */
    public function horasExtra(Request $request)
    {
        return view('public.horas-extra');
    }

    /* Vista de vacaciones
    */
    public function diasVacaciones(Request $request)
    {
        return view('public.dias-vacaciones');
    }

    /* Vista de vacaciones
    */
    public function fechaAguinaldo(Request $request)
    {
        return view('public.fecha-aguinaldo');
    }

    /* Vista de terminancion laboral por mutuo acuerdo
     */
    public function calcularCuantoPagar(Request $request)
    {
        return view('public.calcular-cuanto-pagar');
    }

    /* Vista de terminancion laboral por mutuo acuerdo
     */
    public function beneficiarioDerecho(Request $request)
    {
        return view('public.beneficiario-derecho');
    }

    /* Vista de terminancion laboral por mutuo acuerdo
     */
    public function riesgoLaboralIncapacidad(Request $request)
    {
        return view('public.riesgo-laboral-incapacidad');
    }
     /* Vista de terminancion laboral por mutuo acuerdo
     */
    public function clasificacionIndustrial(Request $request)
    {
        return view('public.clasificacion-industrial');
    }
       /**
     * Vista de vacaciones
     */
    public function vacaciones(Request $request)
    {
        return view('public.vacaciones');
    }


    
    /**
     * Vista de localiza tu CCL informativo
     */
    public function   localizaTuCCLInfo(Request $request)
    {                  
        return view('public.localiza-ccl-info');
    }
     /**
     * Vista de localiza tu CCL
     */
    public function   localizaTuCCL(Request $request)
    {                  
        $cclsUbicaciones = \DB::table('ccls as t1')
             ->select('t1.id', 't1.estado','t1.municipio', 't1.ambito','t1.direccion', 
             't1.url_google', 't1.lat','t1.long', 't1.contacto', 't1.cp', 't1.link')->get();              
        
        $sector_ccl = \DB::table('sector_ccl as t1')
            ->select('t1.id', 't1.sector','t1.subsector', 't1.rama','t1.subrama', 't1.tipo')->get();   
        
        $estados = \DB::table('estados as t1')
            ->select('t1.clave', 't1.nombre', 't1.abreviacion', 't1.cp_min','t1.cp_max', 't1.lat','t1.long')->get();   

        $sectores =  $sector_ccl->unique('sector');
        $subsectores = $sector_ccl->unique('subsector');
        $ramas = $sector_ccl->unique('rama');
        $subramas = $sector_ccl->unique('subrama');

        $datosGaleria = [  ['titulo' => 'Textil', 'descripcion' => 'Empresas que se dedican a la fabricación de hilo y tela son de competencia federal', 'descripcion2' => 'Empresas de venta de telas, hilos, artículos de mercería, o de confección y maquila de prendas de ropa', 'img' => 'textil.png'],
                           ['titulo' => 'Eléctrica', 'descripcion' => 'Centrales eléctricas dedicadas principalmente a la generación y distribución de energía eléctrica', 'descripcion2'=>'Empresas que se dedican a la venta de materiales para trabajo eléctrico o que venden artículos electrónicos.', 'img' => 'electrica.png'], 
                           ['titulo' => 'Cinematográfica', 'descripcion' => 'Empresas que se dedican a la producción, distribución y proyección de películas, como Cinépolis y Cinemex', 'descripcion2'=>'Empresas que no producen o proyectan, sino venden o distribuyen películas en formato físico, como librerías y tiendas departamentales cuya actividad principal es el comercio de productos en general y no solamente la venta de películas', 'img' => 'cinematografica.png'], 
                           ['titulo' => 'Hulera', 'descripcion' => 'Empresas que se dedican al cultivo de la planta de guayule y a la extracción del hule de la planta y a la fabricación de llantas ', 'descripcion2'=>'Empresas que no fabrican, sino solamente distribuyen o venden productos de hule, incluyendo llantas.', 'img' => 'hulera.png'], 
                           ['titulo' => 'Azucarera', 'descripcion' => 'Empresas que se dedican a la producción de azúcar en ingenios azucareros', 'descripcion2'=>'Empresas que no manufacturan el azúcar, sino solamente lo distribuyen o lo comercializan', 'img' => 'azucarera.png'], 
                           ['titulo' => 'Minera', 'descripcion' => 'Empresas que se dediquen a la explotación de minerales metálicos y no metálicos, así como a la extracción de gas y petróleo en minas, canteras y bancos de materiales; así como operaciones en pozos.', 'descripcion2'=>'Empresas que solamente proveen servicios o productos a las empresas extractoras.', 'img' => 'minera.png'], 
                           ['titulo' => 'Metalúrgica y siderúrgica', 'descripcion' => 'Empresas que se dedican a la explotación de los minerales básicos, el beneficio y la fundición de los mismos, así como la obtención de hierro metálico y acero a todas sus formas y ligas y los productos laminados de los mismos, son de competencia federal.', 'descripcion2'=>'Cualquier empresa que compra hierro y lo utiliza para diversa manufactura como maquinaria, muebles, instalaciones y partes para industria.', 'img' => 'metalurgica.png'], 
                           ['titulo' => 'Hidrocarburos', 'descripcion' => 'Empresas que producen gasolina, diésel y gas por medio de la extracción en pozos de explotación, plataformas marinas y refinerías como Pemex', 'descripcion2'=>'Empresas que comercializan la gasolina o el gas natural, como las gasolineras o las gaseras, como G500 o Hidrosina.', 'img' => 'hidrocarburos.png'], 
                           ['titulo' => 'Petroquímica', 'descripcion' => 'Empresas que se dedican a la extracción de combustibles fósiles para su transformación del gas natural y los derivados del petróleo en materias primas.', 'descripcion2'=>'Empresas que no manufacturan sino solamente distribuyen y comercializan productos químicos.', 'img' => 'petroquimica.png'], 
                           ['titulo' => 'Cementera', 'descripcion' => ': Empresas que se dedican a la fabricación de la mezcla de caliza y arcilla calcinada y molida, como Cemex y Holcim-Apasco.', 'descripcion2'=>'Empresas que comercializan cemento para la construcción, como tlapalerías y tiendas dedicadas a materiales para la construcción y mejoras del hogar, como Home Depot.', 'img' => 'cementera.png'], 
                           ['titulo' => 'Calera', 'descripcion' => 'Empresas que se dedican a la calcinación de piedra caliza para producir cal', 'descripcion2'=>'Empresas que solamente distribuyen o comercializan la cal, como tiendas de materiales de construcción', 'img' => 'calera.png'], 
                           ['titulo' => 'Automotriz', 'descripcion' => 'Empresas que se dedican a la fabricación de automóviles, incluyendo autopartes mecánicas o eléctricas. En general, cualquier manufacturera de autopartes es probable que sea de competencia federal. ', 'descripcion2'=>'Empresas que fabrican aditamentos para automóviles, que no son parte del automóvil, como son tapetes, cubiertas de muebles, etc. Empresas que diseñan o programan sistemas electrónicos para coches. Empresas de distribución y ventas de automóviles, como agencias automotrices.', 'img' => 'automotriz.png'], 
                           ['titulo' => 'Química', 'descripcion' => 'Empresas que se dedican a la fabricación de productos químicos, incluyendo la química farmacéutica y medicamentos. ', 'descripcion2'=>'Empresas que distribuyen o comercializan productos químicos o farmacéuticos, farmacias y laboratorios para estudios clínicos.', 'img' => 'quimica.png'], 
                           ['titulo' => 'De celulosa y papel', 'descripcion' => 'Empresas que se dedican a la producción de celulosa y papel, que producen pulpa, papel, cartón y otros productos a base de celulosa. ', 'descripcion2'=>'Empresas que se dedican a la distribución y comercialización de productos de papel, como papelerías o a la fabricación de cajas y material de embalaje en cartón y papel.', 'img' => 'celulosa.png'], 
                           ['titulo' => 'Aceites y grasas vegetales', 'descripcion' => 'Empresas que se dedican a la producción de aceites y grasas vegetales comestibles, extraídos de las oleaginosas, principalmente de soya, canola y cártamo.', 'descripcion2'=>'Empresas que distribuyen o comercializan aceites y grasas vegetales para empresas, tiendas, restaurantes, hoteles, etc.', 'img' => 'aceites.png'], 
                           ['titulo' => 'Productora de alimentos', 'descripcion' => 'Empresas que se dedican a la producción de alimentos, abarcando exclusivamente la fabricación de los que sean empacados, enlatados o envasados al alto vacío o que se destinen a ello.', 'descripcion2'=>'Empresas que no manufacturan ni producen, sino distribuyen o comercializan en una tienda, centro comercial u otra instancia o distribuye alimentos enlatados o envasados al alto vacío.', 'img' => 'alimentos.png'],
                           ['titulo' => 'Elaboradora de bebidas', 'descripcion' => 'Empresas que se dedican a la elaboración de bebidas que sean envasadas o enlatadas al alto vacío o que se destinen a ello, como Coca Cola y Jumex o que purifiquen agua para envasarla.', 'descripcion2'=>'Empresas que distribuyen o comercializan bebidas en un autoservicio, depósitos o alguna otra entidad.', 'img' => 'bebidas.png'], 
                           ['titulo' => 'Ferrocarrilera', 'descripcion' => 'Empresas ferrocarrileras, dedicadas a la industria ferroviaria, incluyendo las actividades de infraestructura, material rodante, señalización, control de tráfico, etc.', 'descripcion2'=>'Empresas que proveen servicios para ferrocarriles, como alimentos, servicios de limpieza, seguridad y combustibles.', 'img' => 'ferrocarrilera.png'], 
                           ['titulo' => 'Maderera', 'descripcion' => 'Empresas que se dedican a la madera básica, que comprende la explotación, extracción, corte y procesado de las maderas, para la producción de aserradero y la fabricación de triplay o aglutinados de madera.', 'descripcion2'=>'Puntos de venta al mayoreo o menudeo de productos de madera básica, empresas que utilizan la madera como insumo para fabricar muebles, casas, laminados de madera, etc.', 'img' => 'maderera.png'], 
                           ['titulo' => 'Vidriera', 'descripcion' => 'Empresas que se dedican a la fabricación de vidrio plano, liso o labrado o envases de vidrio, como Vitro.', 'descripcion2'=>'Puntos de venta al mayoreo o menudeo de productos de vidrio plano, liso, labrado o envases, empresas de construcción que utilizan e instalan vidrio para edificios o casas.', 'img' => 'vidriera.png'], 
                           ['titulo' => 'Tabacalera', 'descripcion' => 'Empresas que comprenden el beneficio o fabricación de productos de tabaco como Philip Morris o British American Tobacco México. ', 'descripcion2'=>'Puntos de venta al mayoreo o menudeo de productos de tabaco.', 'img' => 'tabacalera.png'], 
                           ['titulo' => 'Servicios banca y crédito', 'descripcion' => 'Empresas dedicadas a la Banca comercial, que ofrecen productos financieros como tarjetas bancarias, créditos bancarios, servicios de cuentas bancarias, créditos prendarios (como Nacional Monte de Piedad) etc. ', 'descripcion2'=>'Empresas financieras que no se dedican a la banca múltiple y de desarrollo, como las Sociedades Financieras de Objeto Múltiple (SOFOM), empresas de arrendamiento financiero, casas de bolsa.', 'img' => 'banca.png']];
         
        //dd($datosGaleria->sortBy('titulo'));
        $datosGaleria = collect($datosGaleria)->sortBy('titulo');
        
        
        return view('public.localiza-ccl',  compact("cclsUbicaciones", "sector_ccl", "sectores", "subsectores","ramas", "subramas", "estados", "datosGaleria"));
    }
    /**
     * metodo que muestra el CCL dado  el ambito Federal o Local
     */
    public function   localizaTuCCLAmbito($ambito){       
                
        $cclsUbicaciones = \DB::table('ccls as t1')
             ->select('t1.id', 't1.estado','t1.municipio', 't1.ambito','t1.direccion', 
             't1.url_google', 't1.lat','t1.long', 't1.contacto', 't1.cp', 't1.link')
             ->where('t1.ambito', "=", $ambito)
             ->get();              

        $sector_ccl = \DB::table('sector_ccl as t1')
            ->select('t1.id', 't1.sector','t1.subsector', 't1.rama','t1.subrama', 't1.tipo')->get();   
        
        $estados = \DB::table('estados as t1')
            ->select('t1.clave', 't1.nombre', 't1.abreviacion', 't1.cp_min','t1.cp_max', 't1.lat','t1.long')->get();   

        $sectores =  $sector_ccl->unique('sector');
        $subsectores = $sector_ccl->unique('subsector');
        $ramas = $sector_ccl->unique('rama');
        $subramas = $sector_ccl->unique('subrama');

        $datosGaleria = [['titulo' => 'Textil', 'descripcion' => 'Empresas que se dedican a la fabricación de hilo y tela.', 'img' => 'textil.png'],
                           ['titulo' => 'Eléctrica', 'descripcion' => 'Centrales eléctricas dedicadas principalmente a la generación y distribución de energía eléctrica', 'img' => 'electrica.png'], 
                           ['titulo' => 'Cinematográfica', 'descripcion' => 'Empresas que se dedican a la producción, distribución y proyección de película', 'img' => 'cinematografica.png'], 
                           ['titulo' => 'Hulera', 'descripcion' => 'Entidades que se dedican al cultivo de la planta de guayule y a la extracción del hule de la planta. Empresas que se dedican a la fabricación de llantas.', 'img' => 'hulera.png'], 
                           ['titulo' => 'Azucarera', 'descripcion' => 'Empresas que se dedican a la producción de azúcar', 'img' => 'azucarera.png'], 
                           ['titulo' => 'Minera', 'descripcion' => 'Empresas dedicadas principalmente a la extracción de petróleo y gas, y a la explotación de minerales metálicos y no metálicos', 'img' => 'minera.png'], 
                           ['titulo' => 'Metalúrgica y siderúrgica', 'descripcion' => 'Empresas que se dedican a la explotación de minerales básicos, la fundición de los mismos, la obtención de hierro, acero y los productos laminados.', 'img' => 'metalurgica.png'], 
                           ['titulo' => 'Hidrocarburos', 'descripcion' => 'Empresas que se dedican a la producción de gasolina, diésel y gas', 'img' => 'hidrocarburos.png'], 
                           ['titulo' => 'Petroquímica', 'descripcion' => 'Empresas que se dedican a la extracción de combustibles fósiles. Empresas petroquímicas que transforman el gas natural y los derivados del petróleo.', 'img' => 'petroquimica.png'], 
                           ['titulo' => 'Cementera', 'descripcion' => 'Empresas que se dedican a la fabricación de la mezcla de caliza y arcilla calcinada y molida', 'img' => 'cementera.png'], 
                           ['titulo' => 'Calera', 'descripcion' => 'Empresas que se dedican a la calcinación de piedra caliza y/o que producen cal.', 'img' => 'calera.png'], 
                           ['titulo' => 'Automotriz', 'descripcion' => 'Empresas que se dedican a la fabricación de automóviles incluyendo autopartes mecánicas o eléctricas', 'img' => 'automotriz.png'], 
                           ['titulo' => 'Química', 'descripcion' => 'Empresas que se dedican a la fabricación de productos químicos, incluyendo la química farmacéutica y medicamentos', 'img' => 'quimica.png'], 
                           ['titulo' => 'De celulosa y papel', 'descripcion' => 'Empresas que producen pulpa, papel, cartón y otros productos a base de celulosa.', 'img' => 'celulosa.png'], 
                           ['titulo' => 'De aceites y grasas vegetales', 'descripcion' => 'Empresas que se dedican a la industria alimentaria, para producir aceite extraído de las oleaginosas, principalmente de soya, canola y cártam', 'img' => 'aceites.png'], 
                           ['titulo' => 'Productora de alimentos', 'descripcion' => 'Empresas que se dedican a la producción de alimentos, abarcando exclusivamente la fabricación de los que sean empacados, enlatados o envasados al alto vacío o que se destinen a ello.', 'img' => 'alimentos.png'],
                           ['titulo' => 'Elaboradora de bebidas', 'descripcion' => 'Empresas que se dedican a la elaboración de bebidas que sean envasadas o enlatadas al alto vacío o que se destinen a ello.', 'img' => 'bebidas.png'], 
                           ['titulo' => 'Ferrocarrilera', 'descripcion' => 'Empresas dedicadas a la industria ferroviaria, incluyendo las actividades de infraestructura, material rodante, señalización, control de tráfico, etc.', 'img' => 'ferrocarrilera.png'], 
                           ['titulo' => 'Maderera', 'descripcion' => 'Empresas que se dedican a la explotación, extracción, corte y procesado de las maderas para la fabricación de diversos productos.', 'img' => 'maderera.png'], 
                           ['titulo' => 'Vidriera', 'descripcion' => 'Empresas que se dedican a la fabricación exclusivamente de vidrio plano, liso o labrado o envases de vidrio.', 'img' => 'vidriera.png'], 
                           ['titulo' => 'Tabacalera', 'descripcion' => 'Empresas que se dedican a la fabricación de productos de tabaco', 'img' => 'tabacalera.png'], 
                           ['titulo' => 'Servicios de banca y crédito', 'descripcion' => 'Bancos que ofrecen productos financieros como tarjetas bancarias, créditos bancarios y servicios de cuentas bancaria', 'img' => 'banca.png']];
         
        
        return view('public.localiza-ccl-ambito',  compact("cclsUbicaciones", "sector_ccl", "sectores", "subsectores","ramas", "subramas", "estados", "datosGaleria", "ambito"));
    }

    /**
     * metodo que muestra información de los derechos laboralestrabajadores
     */    
    public function derLabTrabajadores(Request $request){                   
        $datosDerechos = [ ['titulo' => 'Salario', 'descripcion' => 'Es la retribución o pago por el trabajo realizado, que deberá ser en moneda de curso legal (pesos mexicanos), y se debe pagar ya sea por un tiempo (cada semana, quincena o mes trabajado) o por trabajo realizado. No debe ser menor al salario mínimo.', 'arts'=> 'Arts. 5, 82, 85, 90, 91, 92, 93, 94, 95, 96 y 97 Ley Federal del Trabajo  ' ,'img' => 'salario.png'],
                           ['titulo' => 'Aguinaldo', 'descripcion' => 'Es el pago adicional a tu salario (de manera anual) que tu empleador o empleadora debe cubrir a más tardar el día 20 de diciembre de cada año y, debe ser de, por lo menos, 15 días de salario.', 'arts' => 'Art. 87 Ley Federal del Trabajo', 'img' => 'aguinaldo.png'], 
                           ['titulo' => 'Reparto de utilidades', 'descripcion' => 'Las personas trabajadoras tienen derecho a recibir una proporción de las ganancias obtenidas por la empresa.', 'arts'=>'Arts. 117 a 131 Ley Federal del Trabajo','conocemas'=> 'conocemas', 'img' => 'utilidades.png'], 
                           ['titulo' => 'Prima de antigüedad', 'descripcion' => 'Es el pago de 12 días de salario por cada año laborado que se paga independientemente de la causa de separación del trabajo.
                           Si la persona trabajadora renuncia, tendrá derecho a este pago solo si ha cumplido 15 años de servicio. En caso de fallecimiento del trabajador, sus beneficiarios podrán exigir el pago de la prima de antigüedad, no importando que no se hayan cumplido 15 años de servicio.','arts'=>'Art. 162 Ley Federal del Trabajo', 'img' => 'antiguedad.png'], 
                           ['titulo' => 'Jornada laboral regulada', 'descripcion' => 'Las jornadas laborales más comunes establecidas en la Ley Federal del Trabajo son:', 'ul'=>['Diurna. Realizada entre las 6 y las 20 horas. Con duración de 8 horas máximo.','Nocturna. Realizada dentro de las 20 y las 6 horas. Con duración de 7 horas','Mixta. Abarca horarios nocturno y diurno. Con duración de hasta 7.5 horas'], 'arts'=> 'Arts. 58, 59, 60 y 61 Ley Federal del Trabajo', 'img' => 'jornada.png'], 
                           ['titulo' => 'Descanso', 'descripcion' => 'Tienes derecho a un descanso de por lo menos media hora. Si no tomas ese descanso se considera como tiempo efectivamente trabajado. Si no puedes salir del lugar de trabajo para tomar tus alimentos, entonces ese tiempo cuenta como parte de tu jornada de trabajo. Las horas extras permitidas por la LFT serán 3 horas diarias, y solamente 3 veces a la semana. Por cada 6 días de trabajo tienes derecho a un día de descanso con goce de salario. Estos son los días de descanso obligatorio: 1o de enero, 1er lunes de febrero, 3er lunes de marzo, 1o de mayo, 16 de septiembre, 3er lunes de noviembre, 1° de diciembre de cada 6 años, 25 de diciembre y fechas electorales.', 'arts'=>'Arts. 63, 66, 69, 71, 72, 73 y 74 Ley Federal del Trabajo', 'img' => 'descanso.png'], 
                           ['titulo' => 'Vacaciones y prima vacacional', 'descripcion' => 'Tienes derecho a disfrutar de 12 días de vacaciones continuos, por lo menos y podrás distribuir dichos días de vacaciones en la forma y tiempo que así lo requieras. Con la reforma a la LFT que entró en vigor el 1 de enero de 2023, en materia de vacaciones, el tiempo de vacaciones debe aumentar con los años laborados.', 'arts'=>'Arts. 76, 77, 78 y 79 Ley Federal del Trabajo', 'img' => 'vacaciones.png'], 
                           ['titulo' => 'Seguridad social', 'descripcion' => 'Como persona trabajadora tienes derecho a la seguridad social y a los seguros que la integran. El régimen obligatorio de la seguridad social del Instituto Mexicano del Seguro Social (IMSS) incluye diferentes prestaciones. Es importante que tu empresa te registre con tu salario real ante el seguro social, ya que cualquier subregistro impacta en el cálculo de tus prestaciones.', 'arts'=>'Art. 2, 472 y 473 Ley Federal del Trabajo y Arts. 11, 12 y 15 Ley del Seguro Social',  'img' => 'seguridad.png'], 
                           ['titulo' => 'Si eres mujer: acceso a medidas de protección en el embarazo y lactancia', 'descripcion' => 'La persona empleadora debe proteger la salud de las mujeres trabajadoras, manteniéndolas alejadas de labores peligrosas o insalubres, trabajo en horarios nocturnos y en horas extraordinarias durante el embarazo o lactancia.','arts'=>'Arts. 166, 167, 168, 170 y 170 Bis Ley Federal del Trabajo', 'img' => 'mujer.png'], 
                           ['titulo' => 'Licencia de maternidad y paternidad', 'descripcion' => 'Las mujeres trabajadoras cuentan con un periodo de 12 semanas de licencia de maternidad. En el caso de adopción, se tiene derecho a un periodo de 6 semanas de licencia con goce de sueldo, contando a partir del día que el niño/a sea recibido/a en el nuevo hogar. Los hombres trabajadores tienen derecho a una licencia de paternidad de 5 días laborables con goce de sueldo, en casos de nacimiento y/o adopción.','arts'=>'Arts. 170 y 170 Bis Ley Federal del Trabajo', 'img' => 'maternidad.png'], 
                           ['titulo' => 'Acceso a condiciones de seguridad y salud en el trabajo', 'descripcion' => 'Es derecho de los y las trabajadoras contar con un espacio seguro y e higiénico en el centro de trabajo que les proteja de cualquier riesgo. Por lo tanto, es obligación de la persona empleadora adoptar las medidas y acciones adecuadas para la prevención oportuna de accidentes y de enfermedades por motivo del trabajo.','arts'=>'Arts. 2, 472, 473, 474, 475, 475 Bis Ley Federal del Trabajo', 'img' => 'salud.png'], 
                           ['titulo' => 'Contrato de trabajo', 'descripcion' => 'Es tu derecho contar con un documento por el que te comprometes a prestar un trabajo según las condiciones establecidas con la empresa o el empleador, a cambio de recibir un salario. La persona empleadora debe entregarte un ejemplar de este documento.','arts'=>'Arts. 5, 26 y 28 Ley Federal del Trabajo', 'img' => 'contrato.png'], 
                           ['titulo' => 'Conciliación laboral', 'descripcion' => 'Antes de ir a juicio por temas laborales, las personas trabajadoras y empleadoras tienen derecho a intentar la vía de la conciliación, un proceso rápido, gratuito y confidencial que debe resolverse en un máximo de 45 días.', 'arts'=>'Arts. 684-C y 684-E Ley Federal del Trabajo', 'img' => 'conciliacion.png']];
         //['titulo' => 'Asesoría legal', 'descripcion' => 'En caso de requerir asesoría legal puedes contactar a las siguientes autoridades: Procuraduría Federal de la Defensa del Trabajo www.gob.mx/profedet/articulos/ procuradurias-foraneas Instituto Federal de Defensoría Pública Directorio de las oficinas en los estados https://www.ifdp.cjf.gob.mx/resources/directorios/asesores/Directori oLaboral_08022023.pdf Todos los servicios son gratuitos:', 'img' => 'asesoria.png']
        return view('public.derechos-laborales-trabajadores', compact("datosDerechos"));
        
    }
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
        $fechasNoDisponibles = null;
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
            $totalOficinas = DB::table('ccls')->where('status', 1)
            ->count();

            // Obtenemos las oficinas configuradas listas para agendar citas
            $listasCitas = DB::table('ccls as t1')
            ->join('citas_configuracion_oficinas as t2', 't1.id', '=', 't2.id_ccls')
            ->join('users as t3', 't1.id', '=', 't3.id_ccls')
            ->where('t3.perfil', '=', 'administrador')
            ->where('t1.status', 1)
            ->where('t3.status', '=', 1)
            ->count();

            // Obtenemos administadores
            $usuariosAdmin = User::where('perfil', 'administrador')->where('status', 1)->count();
            $usuariosCs = User::where('perfil', 'conciliador')->where('status', 1)->count();
            $usuariosAux = User::where('perfil', 'Auxiliar')->where('status', 1)->count();

            /* dd($totalOficinas, $listasCitas, $usuariosAdmin, $usuariosCs, $usuariosAux); */
        }

        if($auth_user->perfil === 'administrador'){

            // Obtenemos la disponibilidad por horarios
            $disponible = CitasDisponibilidadOficinas::where('id_ccls', $auth_user->id_ccls)
            ->where('id_administrador', $auth_user->id)
            ->where('status', 1)
            ->orderBy('horario')
            ->get();
            
            // Obtenemos las fechas disponibles por oficina
            $fechasDisponibles = DB::table('citas_disponibilidad_fechas_oficinas as t1')
            ->where('t1.id_ccls', $auth_user->id_ccls)
            ->where('t1.id_administrador', $auth_user->id)
            ->where('t1.status', 1)
            ->count();

            $fechasNoDisponibles = DB::table('citas_disponibilidad_fechas_oficinas as t1')
            ->where('t1.id_ccls', $auth_user->id_ccls)
            ->where('t1.id_administrador', $auth_user->id)
            ->where('t1.status', 0)
            ->count();            

            // Obtenemos la configuración de días
            $disponbilidadOficina = CitasConfiguracionOficinas::where('id_administrador', $auth_user->id)
            ->where('id_ccls', $auth_user->id_ccls)
            ->where('status', 1)
            ->where('aplica', '<=', $fechaActual)
            ->first();

            // Obtenemos el número de conciliadores registrados por oficina
            $conciliadores = DB::table('users')            
            ->where('id_ccls', $auth_user->id_ccls)
            ->where('perfil', 'conciliador')
            ->where('status', 1)
            ->count();
            
            // Obtenemos el número de auxiliares registrados por oficina
            $auxiliares = DB::table('users')        
            ->where('id_ccls', $auth_user->id_ccls)
            ->where('perfil', 'auxiliar')
            ->where('status', 1)
            ->count();

            // Obtenemos las citas atendidas
            $citasAtendidas = DB::table('citas_registradas as t1')
            ->select('t1.status_conciliador')
            ->where('t1.id_ccls', $auth_user->id_ccls)
            ->where('t1.cita_fecha', '=', $fechaActual)
            ->where('t1.status_conciliador', '=', 1)
            ->Where('t1.status', '=', 1)
            ->count();

            // Obtenemos las citas pendientes
            $citasPendientes = DB::table('citas_registradas as t1')
            ->select('t1.status_conciliador')
            ->where('t1.id_ccls', $auth_user->id_ccls)
            ->where('t1.cita_fecha', '=', $fechaActual)
            ->where('t1.status_conciliador', '=', 2)
            ->Where('t1.status', '=', 1)
            ->count();        
            
            // Obtenemos las citas canceladas
            $citasCanceladas = DB::table('citas_registradas as t1')
            ->select('t1.status_conciliador')
            ->where('t1.id_ccls', $auth_user->id_ccls)
            ->where('t1.cita_fecha', '=', $fechaActual)
            ->where(function($query) {
                $query->where('t1.status_conciliador', '=', 0)
                      ->orWhere('t1.status', '=', 0);
            })
            ->count();

            // Obtenemos las citas atendidas por semana
            $citasAtendidasSemanaQ = DB::table('citas_registradas as t1')
            ->select('t1.cita_fecha')
            ->where('t1.id_ccls', $auth_user->id_ccls)
            ->where('t1.status_conciliador', '=', '1')
            ->Where('t1.status', '=', 1)
            ->get();
            
            // Comparamos el número de semana de cita con la fecha actual y sumamos la semanas atendidas actual
            if(count($citasAtendidasSemanaQ) > 0){

                foreach ($citasAtendidasSemanaQ as $citaSemana) {

                    if( date( "W", strtotime( $citaSemana->cita_fecha ) ) === date( "W", strtotime( now() ) ) ){
                        $citasAtendidasSemana++;
                    }
                }

            }

            // Obtenemos las citas pendientes por semana             
            $citasPendientesSemanaQ = DB::table('citas_registradas as t1')
            ->select('t1.cita_fecha')
            ->where('t1.id_ccls', $auth_user->id_ccls)
            ->where('t1.status_conciliador', '=', '2')
            ->Where('t1.status', '=', 1)
            ->get();

            // Comparamos el número de semana de cita con la fecha actual y sumamos la semanas pendientes actual
            if(count($citasPendientesSemanaQ) > 0){

                foreach ($citasPendientesSemanaQ as $citaSemana) {

                    if( date( "W", strtotime( $citaSemana->cita_fecha ) ) === date( "W", strtotime( $fechaActual ) ) ){
                        $citasPendientesSemana++;
                    }
                }

            }

            // Obtenemos las citas canceladas por semana
            $citasCanceladasSemanaQ = DB::table('citas_registradas as t1')
            ->select('t1.cita_fecha')
            ->where('t1.id_ccls', $auth_user->id_ccls)
            ->where(function($query) {
                $query->where('t1.status_conciliador', '=', 0)
                      ->orWhere('t1.status', '=', 0);
            })
            ->get();            

            // Comparamos el número de semana de cita con la fecha actual y sumamos la semanas canceladas actual
            if(count($citasCanceladasSemanaQ) > 0){

                foreach ($citasCanceladasSemanaQ as $citaSemana) {

                    if( date( "W", strtotime( $citaSemana->cita_fecha ) ) === date( "W", strtotime( now() ) ) ){
                        $citasCanceladasSemana++;
                    }
                }

            }

        }

        if($auth_user->perfil === 'conciliador'){

            // Obtenemos las citas atendidas
            $citasAtendidas = DB::table('citas_registradas as t1')
            ->select('t1.status_conciliador')
            ->where('t1.id_ccls', $auth_user->id_ccls)
            ->where('t1.cita_fecha', '=', $fechaActual)
            ->where('t1.status_conciliador', '=', '1')
            ->Where('t1.status', '=', 1)
            ->count();

            // Obtenemos las citas pendientes
            $citasPendientes = DB::table('citas_registradas as t1')
            ->select('t1.status_conciliador')
            ->where('t1.id_ccls', $auth_user->id_ccls)
            ->where('t1.cita_fecha', '=', $fechaActual)
            ->where('t1.status_conciliador', '=', '2')
            ->Where('t1.status', '=', 1)
            ->count();
            
            // Obtenemos las citas canceladas
            $citasCanceladas = DB::table('citas_registradas as t1')
            ->select('t1.status_conciliador')
            ->where('t1.id_ccls', $auth_user->id_ccls)
            ->where('t1.cita_fecha', '=', $fechaActual)
            ->where(function($query) {
                $query->where('t1.status_conciliador', '=', 0)
                      ->orWhere('t1.status', '=', 0);
            })
            ->count();

            // Obtenemos las citas atendidas por semana
            $citasAtendidasSemanaQ = DB::table('citas_registradas as t1')
            ->select('t1.cita_fecha')
            ->where('t1.id_ccls', $auth_user->id_ccls)
            ->where('t1.status_conciliador', '=', '1')
            ->Where('t1.status', '=', 1)
            ->get();
            
            // Comparamos el número de semana de cita con la fecha actual y sumamos la semanas atendidas actual
            if(count($citasAtendidasSemanaQ) > 0){

                foreach ($citasAtendidasSemanaQ as $citaSemana) {

                    if( date( "W", strtotime( $citaSemana->cita_fecha ) ) === date( "W", strtotime( now() ) ) ){
                        $citasAtendidasSemana++;
                    }
                }

            }

            // Obtenemos las citas pendientes por semana             
            $citasPendientesSemanaQ = DB::table('citas_registradas as t1')
            ->select('t1.cita_fecha')
            ->where('t1.id_ccls', $auth_user->id_ccls)
            ->where('t1.status_conciliador', '=', '2')
            ->Where('t1.status', '=', 1)
            ->get();

            // Comparamos el número de semana de cita con la fecha actual y sumamos la semanas pendientes actual
            if(count($citasPendientesSemanaQ) > 0){

                foreach ($citasPendientesSemanaQ as $citaSemana) {

                    if( date( "W", strtotime( $citaSemana->cita_fecha ) ) === date( "W", strtotime( $fechaActual ) ) ){
                        $citasPendientesSemana++;
                    }
                }

            }

            // Obtenemos las citas canceladas por semana
            $citasCanceladasSemanaQ = DB::table('citas_registradas as t1')
            ->select('t1.cita_fecha')
            ->where('t1.id_ccls', $auth_user->id_ccls)
            ->where(function($query) {
                $query->where('t1.status_conciliador', '=', 0)
                      ->orWhere('t1.status', '=', 0);
            })
            ->get();

            // Comparamos el número de semana de cita con la fecha actual y sumamos la semanas canceladas actual
            if(count($citasCanceladasSemanaQ) > 0){

                foreach ($citasCanceladasSemanaQ as $citaSemana) {

                    if( date( "W", strtotime( $citaSemana->cita_fecha ) ) === date( "W", strtotime( now() ) ) ){
                        $citasCanceladasSemana++;
                    }
                }

            }

        }
        
        return view('dashboards.dashboard', compact('assets', 'estados', 'cclsUbicaciones', 'auth_user', 
        'disponible', 'fechasDisponibles', 'fechasNoDisponibles' ,'citasAtendidas', 'citasPendientes', 'citasCanceladas', 
        'disponbilidadOficina', 'conciliadores', 'auxiliares', 'citasAtendidasSemana', 
        'citasPendientesSemana', 'citasCanceladasSemana', 'totalOficinas', 'listasCitas',
        'usuariosAdmin', 'usuariosCs', 'usuariosAux'));
    }

    /*
     * Menu Style Routs
     */
    public function horizontal(Request $request)
    {
        $assets = ['chart', 'animation'];
        return view('menu-style.horizontal',compact('assets'));
    }
    public function dualhorizontal(Request $request)
    {
        $assets = ['chart', 'animation'];
        return view('menu-style.dual-horizontal',compact('assets'));
    }
    public function dualcompact(Request $request)
    {
        $assets = ['chart', 'animation'];
        return view('menu-style.dual-compact',compact('assets'));
    }
    public function boxed(Request $request)
    {
        $assets = ['chart', 'animation'];
        return view('menu-style.boxed',compact('assets'));
    }
    public function boxedfancy(Request $request)
    {
        $assets = ['chart', 'animation'];
        return view('menu-style.boxed-fancy',compact('assets'));
    }

    /*
     * Pages Routs
     */
    public function billing(Request $request)
    {
        return view('special-pages.billing');
    }

    public function calender(Request $request)
    {
        $assets = ['calender'];
        return view('special-pages.calender',compact('assets'));
    }

    public function kanban(Request $request)
    {
        return view('special-pages.kanban');
    }

    public function pricing(Request $request)
    {
        return view('special-pages.pricing');
    }

    public function rtlsupport(Request $request)
    {
        return view('special-pages.rtl-support');
    }

    public function timeline(Request $request)
    {
        return view('special-pages.timeline');
    }


    /*
     * Widget Routs
     */
    public function widgetbasic(Request $request)
    {
        return view('widget.widget-basic');
    }
    public function widgetchart(Request $request)
    {
        $assets = ['chart'];
        return view('widget.widget-chart', compact('assets'));
    }
    public function widgetcard(Request $request)
    {
        return view('widget.widget-card');
    }

    /*
     * Maps Routs
     */
    public function google(Request $request)
    {
        return view('maps.google');
    }
    public function vector(Request $request)
    {
        return view('maps.vector');
    }

    /*
     * Auth Routs
     */
    public function signin(Request $request)
    {
        return view('auth.login');
    }
    public function signup(Request $request)
    {
        return view('auth.register');
    }
    public function confirmmail(Request $request)
    {
        return view('auth.confirm-mail');
    }
    public function lockscreen(Request $request)
    {
        return view('auth.lockscreen');
    }
    public function recoverpw(Request $request)
    {
        return view('auth.recoverpw');
    }
    public function userprivacysetting(Request $request)
    {
        return view('auth.user-privacy-setting');
    }

    /*
     * Error Page Routs
     */

    public function error404(Request $request)
    {
        return view('errors.error404');
    }

    public function error500(Request $request)
    {
        return view('errors.error500');
    }
    public function maintenance(Request $request)
    {
        return view('errors.maintenance');
    }

    /*
     * uisheet Page Routs
     */
    public function uisheet(Request $request)
    {
        return view('uisheet');
    }

    /*
     * Form Page Routs
     */
    public function element(Request $request)
    {
        return view('forms.element');
    }

    public function wizard(Request $request)
    {
        return view('forms.wizard');
    }

    public function validation(Request $request)
    {
        return view('forms.validation');
    }

     /*
     * Table Page Routs
     */
    public function bootstraptable(Request $request)
    {
        return view('table.bootstraptable');
    }

    public function datatable(Request $request)
    {
        return view('table.datatable');
    }

    /*
     * Icons Page Routs
     */

    public function solid(Request $request)
    {
        return view('icons.solid');
    }

    public function outline(Request $request)
    {
        return view('icons.outline');
    }

    public function dualtone(Request $request)
    {
        return view('icons.dualtone');
    }

    public function colored(Request $request)
    {
        return view('icons.colored');
    }

    /*
     * Extra Page Routs
     */
    public function privacypolicy(Request $request)
    {
        return view('privacy-policy');
    }
    public function termsofuse(Request $request)
    {
        return view('terms-of-use');
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
