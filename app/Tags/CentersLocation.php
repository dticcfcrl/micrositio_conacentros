<?php

namespace App\Tags;

use Statamic\Tags\Tags;

class CentersLocation extends Tags
{
    /**
     * The {{ centers_location:locations }} tag.
     *
     * @return string|array
     */
    public function locations()
    {
        $ambito = $this->params->get('ambito');

        $cclsUbicaciones = \DB::table('ccls as t1')
        ->select('t1.id', 't1.estado','t1.municipio', 't1.ambito','t1.direccion', 
        't1.url_google', 't1.lat','t1.long', 't1.contacto', 't1.cp', 't1.link','t1.liga_cita', 't1.liga_cita_local')
        ->where('t1.ambito', "=", $ambito)
        ->where('t1.status', 1)->get();              
        $cclsUbicaciones = collect($cclsUbicaciones)->sortBy('estado');
        $cclsUbicaciones = json_encode($cclsUbicaciones);
        return $cclsUbicaciones;
    }

    /**
     * The {{ centers_location:states }} tag.
     *
     * @return string|array
     */
    public function states()
    {
        $estados = \DB::table('estados as t1')
            ->select('t1.clave', 't1.nombre', 't1.abreviacion', 't1.cp_min','t1.cp_max', 't1.lat','t1.long')->get();   

        $estados = collect($estados)->sortBy('nombre');
        $estados = json_encode($estados);
        
        return $estados;
    }
    
    public function statesList()
    {
        
        $estados = \DB::table('estados as t1')
        ->select('t1.clave', 't1.nombre', 't1.abreviacion', 't1.cp_min','t1.cp_max', 't1.lat','t1.long')->orderBy('t1.nombre')
        ->get()->toJson();

        $estados = json_decode($estados, true);

        return $estados;
    }
}
