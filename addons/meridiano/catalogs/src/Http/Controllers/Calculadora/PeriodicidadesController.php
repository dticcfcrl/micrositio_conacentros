<?php

namespace Meridiano\Catalogs\Http\Controllers\Calculadora;

use Illuminate\Http\Request;
use Statamic\Http\Controllers\CP\CpController;
use Illuminate\Support\Facades\DB;

class PeriodicidadesController extends CpController
{
    public function index()
    {
        $this->authorize('view calculadora');
        $periodicidades = DB::table('calculadora_periodicidad')->get();
        return view('catalog::calculadora.periodicidades.index', compact('periodicidades'));
    }

    public function create()
    {
        $this->authorize('view calculadora');
        return view('catalog::calculadora.periodicidades.create');
    }

    public function store(Request $request)
    {
        $this->authorize('view calculadora');
        DB::table('calculadora_periodicidad')->insert([
            'nombre' => $request->nombre,
            'dias' => $request->dias,
        ]);
        return redirect()->to(cp_route('calculadora.periodicidades'))->withSuccess('Registro creado');
    }

    public function edit($id)
    {
        $this->authorize('view calculadora');
        $periodicidad = DB::table('calculadora_periodicidad')->where('id', $id)->first();
        return view('catalog::calculadora.periodicidades.edit', compact('periodicidad'));
    }

    public function update(Request $request, $id)
    {
        $this->authorize('view calculadora');
        DB::table('calculadora_periodicidad')->where('id', $id)->update([
            'nombre' => $request->nombre,
            'dias' => $request->dias,
        ]);
        return redirect()->to(cp_route('calculadora.periodicidades'))->withSuccess('Registro actualizado');
    }

    public function destroy($id)
    {
        $this->authorize('view calculadora');
        DB::table('calculadora_periodicidad')->where('id', $id)->delete();
        return redirect()->to(cp_route('calculadora.periodicidades'))->withSuccess('Registro eliminado');
    }
}
