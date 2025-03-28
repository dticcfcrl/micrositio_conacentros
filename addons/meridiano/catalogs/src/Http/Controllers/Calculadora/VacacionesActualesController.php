<?php
namespace Meridiano\Catalogs\Http\Controllers\Calculadora;

use Illuminate\Http\Request;
use Statamic\Http\Controllers\CP\CpController;
use Illuminate\Support\Facades\DB;

class VacacionesActualesController extends CpController
{
    public function index()
    {
        $this->authorize('view calculadora');
        $vacaciones = DB::table('calculadora_dias_vacaciones_actual')->paginate(20);
        return view('catalog::calculadora.vacaciones_actuales.index', compact('vacaciones'));
    }

    public function create()
    {
        $this->authorize('view calculadora');
        return view('catalog::calculadora.vacaciones_actuales.create');
    }

    public function store(Request $request)
    {
        $this->authorize('view calculadora');
        DB::table('calculadora_dias_vacaciones_actual')->insert([
            'antiguedad' => $request->antiguedad,
            'dias' => $request->dias,
        ]);
        return redirect()->to(cp_route('calculadora.vacaciones_actuales'))->withSuccess('Registro creado');
    }

    public function edit($id)
    {
        $this->authorize('view calculadora');
        $vacacion = DB::table('calculadora_dias_vacaciones_actual')->where('id', $id)->first();
        return view('catalog::calculadora.vacaciones_actuales.edit', compact('vacacion'));
    }

    public function update(Request $request, $id)
    {
        $this->authorize('view calculadora');
        DB::table('calculadora_dias_vacaciones_actual')->where('id', $id)->update([
            'antiguedad' => $request->antiguedad,
            'dias' => $request->dias,
        ]);
        return redirect()->to(cp_route('calculadora.vacaciones_actuales'))->withSuccess('Registro actualizado');
    }

    public function destroy($id)
    {
        $this->authorize('view calculadora');
        DB::table('calculadora_dias_vacaciones_actual')->where('id', $id)->delete();
        return redirect()->to(cp_route('calculadora.vacaciones_actuales'))->withSuccess('Registro eliminado');
    }
}
