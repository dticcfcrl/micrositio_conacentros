<?php

namespace Meridiano\Catalogs\Http\Controllers;

use Statamic\Http\Controllers\CP\CpController;
use Illuminate\Support\Facades\DB;

class CalculadoraController extends CpController
{
    public function index()
    {
        $this->authorize('view calculadora');
        $ano_vigencia = DB::table('calculadora_configuraciones')
        ->where('nombre', 'año de vigencia de vacaciones')
        ->value('valor');

        $ano_vigencia_entero = intval($ano_vigencia);
        return view('catalog::calculadora.index', compact('ano_vigencia_entero'));
    }

    public function periodicidades()
    {
        $this->authorize('view calculadora');

        return view('catalog::calculadora.periodicidades.index');
    }
}