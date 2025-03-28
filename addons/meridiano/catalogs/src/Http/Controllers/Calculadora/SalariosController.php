<?php

namespace Meridiano\Catalogs\Http\Controllers\Calculadora;

use Illuminate\Http\Request;
use Statamic\Http\Controllers\CP\CpController;
use Illuminate\Support\Facades\DB;

class SalariosController extends CpController
{
    public function index(Request $request)
    {
        $this->authorize('view calculadora');
        $query = DB::table('calculadora_salarios')
            ->join('calculadora_profesiones', 'calculadora_salarios.id_profesion', '=', 'calculadora_profesiones.id')
            ->select('calculadora_salarios.*', 'calculadora_profesiones.profesion')
            ->orderBy('anio', 'desc')
            ->orderBy('zona', 'asc')
            ->orderBy('calculadora_profesiones.profesion', 'asc');

        if ($request->has('search') && $request->search) {
            $query->where('calculadora_profesiones.profesion', 'like', '%' . $request->search . '%');
        }

        if ($request->has('anio') && $request->anio) {
            $query->where('calculadora_salarios.anio', $request->anio);
        }

        if ($request->has('zona') && $request->zona) {
            $query->where('calculadora_salarios.zona', $request->zona);
        }

        $salarios = $query->paginate(25);

        return view('catalog::calculadora.salarios.index', compact('salarios'));
    }

    public function create()
    {
        $this->authorize('view calculadora');
        $profesiones = DB::table('calculadora_profesiones')->get();
        return view('catalog::calculadora.salarios.create', compact('profesiones'));
    }

    public function store(Request $request)
    {
        $this->authorize('view calculadora');
        DB::table('calculadora_salarios')->insert([
            'id_profesion' => $request->id_profesion,
            'salario' => $request->salario,
            'zona' => $request->zona,
            'anio' => $request->anio,
        ]);
        return redirect()->to(cp_route('calculadora.salarios'))->withSuccess('Registro creado');
    }

    public function edit($id)
    {
        $this->authorize('view calculadora');
        $salario = DB::table('calculadora_salarios')->where('id', $id)->first();
        $profesiones = DB::table('calculadora_profesiones')->get();
        return view('catalog::calculadora.salarios.edit', compact('salario', 'profesiones'));
    }

    public function update(Request $request, $id)
    {
        $this->authorize('view calculadora');
        DB::table('calculadora_salarios')->where('id', $id)->update([
            'id_profesion' => $request->id_profesion,
            'salario' => $request->salario,
            'zona' => $request->zona,
            'anio' => $request->anio,
        ]);
        return redirect()->to(cp_route('calculadora.salarios'))->withSuccess('Registro actualizado');
    }

    public function destroy($id)
    {
        $this->authorize('view calculadora');
        DB::table('calculadora_salarios')->where('id', $id)->delete();
        return redirect()->to(cp_route('calculadora.salarios'))->withSuccess('Registro eliminado');
    }
}