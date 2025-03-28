<?php
namespace Meridiano\Catalogs\Http\Controllers\Calculadora;

use Illuminate\Http\Request;
use Statamic\Http\Controllers\CP\CpController;
use Illuminate\Support\Facades\DB;

class VacacionesAnterioresController extends CpController
{
    public function index()
    {
        $this->authorize('view calculadora');
        $vacaciones = DB::table('calculadora_dias_vacaciones_anterior')->paginate(20);
        return view('catalog::calculadora.vacaciones_anteriores.index', compact('vacaciones'));
    }

    public function create()
    {
        $this->authorize('view calculadora');
        return view('catalog::calculadora.vacaciones_anteriores.create');
    }

    public function store(Request $request)
    {
        $this->authorize('view calculadora');
        DB::table('calculadora_dias_vacaciones_anterior')->insert([
            'antiguedad' => $request->antiguedad,
            'dias' => $request->dias,
        ]);
        return redirect()->to(cp_route('calculadora.vacaciones_anteriores'))->withSuccess('Registro creado');
    }

    public function edit($id)
    {
        $this->authorize('view calculadora');
        $vacacion = DB::table('calculadora_dias_vacaciones_anterior')->where('id', $id)->first();
        return view('catalog::calculadora.vacaciones_anteriores.edit', compact('vacacion'));
    }

    public function update(Request $request, $id)
    {
        $this->authorize('view calculadora');
        DB::table('calculadora_dias_vacaciones_anterior')->where('id', $id)->update([
            'antiguedad' => $request->antiguedad,
            'dias' => $request->dias,
        ]);
        return redirect()->to(cp_route('calculadora.vacaciones_anteriores'))->withSuccess('Registro actualizado');
    }

    public function destroy($id)
    {
        $this->authorize('view calculadora');
        DB::table('calculadora_dias_vacaciones_anterior')->where('id', $id)->delete();
        return redirect()->to(cp_route('calculadora.vacaciones_anteriores'))->withSuccess('Registro eliminado');
    }
}
