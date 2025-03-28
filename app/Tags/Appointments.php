<?php

namespace App\Tags;

use Statamic\Tags\Tags;
use Illuminate\Support\Facades\DB;
use App\Models\CitasRegistrada;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class Appointments extends Tags
{
    /**
     * The {{ appointments }} tag.
     *
     * @return string|array
     */
    public function index()
    {
        //
    }

    /**
     * The {{ appointments:example }} tag.
     *
     * @return string|array
     */
    public function states()
    {        
        $estados = DB::table('estados as t1')->select('t1.clave', 't1.nombre')->get()->toJson();
        $estados = json_decode($estados, true);
        
        return $estados;
    }

   
    /**
     * The {{ appointments:example }} tag.
     *
     * @return string|array
     */
    public function consultFolio() {
        $folio = request()->input('folio');
        if(!$folio) {
            $folio = $this->params->get('folio');
        }
        $bandera_consulta = request()->input('consulta');
        $ayer = Carbon::now()->subDay()->format('Y-m-d');
        $folio_informacion = CitasRegistrada::where('cita_folio', $folio)
                                ->where("cita_fecha", '>=', $ayer)
                                ->first();
            
        //Si no hay folio, regresamos a la pantalla principal
        if($folio_informacion == null){
            return [
                'error_codigo' => 1,
            ];
        }
        
        //Obtenemos datos de oficina
        $oficina = DB::table('ccls as t1')->select('t1.id','t1.estado','t1.municipio', 't1.direccion', 't1.url_google', 't1.lat', 't1.long')
        ->where('t1.id', '=', $folio_informacion->id_ccls)->get()->toJson();
        $oficina = json_decode($oficina, true);
        
        if($oficina) {
            return [
                    'error_codigo' => 0,
                    'folio_informacion' => $folio_informacion,
                    'oficina' => $oficina[0],
                    'bandera_consulta' => $bandera_consulta
                ];
        } else {
            return [
                'error_codigo' => 2,
            ];
        }
    }
}
