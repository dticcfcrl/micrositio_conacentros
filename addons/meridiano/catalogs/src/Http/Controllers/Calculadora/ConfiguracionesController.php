<?php
namespace Meridiano\Catalogs\Http\Controllers\Calculadora;

use Illuminate\Http\Request;
use Statamic\Http\Controllers\CP\CpController;
use Illuminate\Support\Facades\DB;

class ConfiguracionesController extends CpController
{
    public function index()
    {
        $this->authorize('view calculadora');
        $configuraciones = DB::table('calculadora_configuraciones')->paginate(15);
        return view('catalog::calculadora.configuraciones.index', compact('configuraciones'));
    }

    public function create()
    {
        $this->authorize('view calculadora');
        return view('catalog::calculadora.configuraciones.create');
    }

    public function store(Request $request)
    {
        $this->authorize('view calculadora');
        DB::table('calculadora_configuraciones')->insert([
            'nombre' => $request->nombre,
            'valor' => $request->valor,
            'descripcion' => $request->descripcion,
        ]);
        return redirect()->to(cp_route('calculadora.configuraciones'))->withSuccess('Registro creado');
    }

    public function edit($id)
    {
        $this->authorize('view calculadora');
        $configuracion = DB::table('calculadora_configuraciones')->where('id', $id)->first();
        return view('catalog::calculadora.configuraciones.edit', compact('configuracion'));
    }

    public function update(Request $request, $id)
    {
        $this->authorize('view calculadora');
        DB::table('calculadora_configuraciones')->where('id', $id)->update([
            'valor' => $request->valor,
        ]);
        return redirect()->to(cp_route('calculadora.configuraciones'))->withSuccess('Registro actualizado');
    }
}
