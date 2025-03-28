<?php
namespace Meridiano\Catalogs\Http\Controllers\Chatbot;

use Illuminate\Http\Request;
use Statamic\Http\Controllers\CP\CpController;
use Illuminate\Support\Facades\DB;

class TopicosController extends CpController
{
    public function index()
    {
        $this->authorize('view chatbot');
        $topicos = DB::table('chatbot_topicos')->orderBy('orden', 'asc')->paginate(25);
        return view('catalog::chatbot.topicos.index', compact('topicos'));
    }

    public function create()
    {
        $this->authorize('view chatbot');
        return view('catalog::chatbot.topicos.create');
    }

    public function store(Request $request)
    {
        $this->authorize('view chatbot');
        DB::table('chatbot_topicos')->insert([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'orden' => $request->orden,
        ]);
        return redirect()->to(cp_route('chatbot.topicos'))->withSuccess('Registro creado');
    }

    public function edit($id)
    {
        $this->authorize('view chatbot');
        $topico = DB::table('chatbot_topicos')->where('id', $id)->first();
        return view('catalog::chatbot.topicos.edit', compact('topico'));
    }

    public function update(Request $request, $id)
    {
        $this->authorize('view chatbot');
        DB::table('chatbot_topicos')->where('id', $id)->update([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'orden' => $request->orden,
        ]);
        return redirect()->to(cp_route('chatbot.topicos'))->withSuccess('Registro actualizado');
    }

    public function destroy($id)
    {
        $this->authorize('view chatbot');
        DB::table('chatbot_topicos')->where('id', $id)->delete();
        return redirect()->to(cp_route('chatbot.topicos'))->withSuccess('Registro eliminado');
    }
}
