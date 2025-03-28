<?php
namespace Meridiano\Catalogs\Http\Controllers\Chatbot;

use Illuminate\Http\Request;
use Statamic\Http\Controllers\CP\CpController;
use Illuminate\Support\Facades\DB;

class SubTopicosController extends CpController
{
    public function index()
    {
        $this->authorize('view chatbot');
        $subtopicos = DB::table('chatbot_subtopicos')->orderBy('nombre', 'asc')->paginate(25);
        return view('catalog::chatbot.subtopicos.index', compact('subtopicos'));
    }

    public function create()
    {
        $this->authorize('view chatbot');
        return view('catalog::chatbot.subtopicos.create');
    }

    public function store(Request $request)
    {
        $this->authorize('view chatbot');
        DB::table('chatbot_subtopicos')->insert([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
        ]);
        return redirect()->to(cp_route('chatbot.subtopicos'))->withSuccess('Registro creado');
    }

    public function edit($id)
    {
        $this->authorize('view chatbot');
        $subtopico = DB::table('chatbot_subtopicos')->where('id', $id)->first();
        return view('catalog::chatbot.subtopicos.edit', compact('subtopico'));
    }

    public function update(Request $request, $id)
    {
        $this->authorize('view chatbot');
        DB::table('chatbot_subtopicos')->where('id', $id)->update([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
        ]);
        return redirect()->to(cp_route('chatbot.subtopicos'))->withSuccess('Registro actualizado');
    }

    public function destroy($id)
    {
        $this->authorize('view chatbot');
        DB::table('chatbot_subtopicos')->where('id', $id)->delete();
        return redirect()->to(cp_route('chatbot.subtopicos'))->withSuccess('Registro eliminado');
    }
}
