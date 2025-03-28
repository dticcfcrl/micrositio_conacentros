<?php

namespace App\Tags;

use Illuminate\Support\Facades\DB;
use Statamic\Tags\Tags;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Collection;

class Calculator extends Tags
{
    public function periods()
    {
        $peridiocidades = \DB::table('calculadora_periodicidad')
            ->select('id', 'nombre', 'dias')->orderBy('dias')->get()->toJson();
        $peridiocidades = json_decode($peridiocidades, true);

        return $peridiocidades;
    }

    public function profesions()
    {
        $profesiones = \DB::table('calculadora_profesiones')
            ->select('id', 'profesion')->where('id', ">", 1)
            ->orderBy('id')->get()->toJson();

        $profesiones = json_decode($profesiones, true);
        
        return $profesiones;
    }
}
