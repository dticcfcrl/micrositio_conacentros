<?php

namespace Meridiano\Catalogs\Http\Controllers\Calculadora;

use Illuminate\Http\Request;
use Statamic\Http\Controllers\CP\CpController;
use Illuminate\Support\Facades\DB;

class ProfesionesController extends CpController
{
    public function index(Request $request)
    {
        $this->authorize('view calculadora');
        $query = DB::table('calculadora_profesiones');
        
        if ($request->has('search')) {
            $query->where('profesion', 'like', '%' . $request->search . '%');
        }
        
        $profesiones = $query->paginate(20);
        return view('catalog::calculadora.profesiones.index', compact('profesiones'));
    }

    public function create()
    {
        $this->authorize('view calculadora');
        return view('catalog::calculadora.profesiones.create');
    }

    public function store(Request $request)
    {
        $this->authorize('view calculadora');
        DB::table('calculadora_profesiones')->insert([
            'profesion' => $request->profesion,
        ]);
        return redirect()->to(cp_route('calculadora.profesiones'))->withSuccess('Registro creado');
    }

    public function edit($id)
    {
        $this->authorize('view calculadora');
        $profesion = DB::table('calculadora_profesiones')->where('id', $id)->first();
        return view('catalog::calculadora.profesiones.edit', compact('profesion'));
    }

    public function update(Request $request, $id)
    {
        $this->authorize('view calculadora');
        DB::table('calculadora_profesiones')->where('id', $id)->update([
            'profesion' => $request->profesion,
        ]);
        return redirect()->to(cp_route('calculadora.profesiones'))->withSuccess('Registro actualizado');
    }

    public function destroy($id)
    {
        $this->authorize('view calculadora');
        // Verificar si hay salarios asociados a la profesión
        $associatedSalaries = DB::table('calculadora_salarios')->where('id_profesion', $id)->count();

        if ($associatedSalaries > 0) {
            return redirect()->to(cp_route('calculadora.profesiones'))->with('error', 'No se puede eliminar la profesión porque hay salarios asociados a ella.');
        }
        DB::table('calculadora_profesiones')->where('id', $id)->delete();
        return redirect()->to(cp_route('calculadora.profesiones'))->withSuccess('Registro eliminado');
    }
}