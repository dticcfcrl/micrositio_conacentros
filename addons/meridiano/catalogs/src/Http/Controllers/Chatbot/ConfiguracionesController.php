<?php
namespace Meridiano\Catalogs\Http\Controllers\Chatbot;

use Illuminate\Http\Request;
use Statamic\Http\Controllers\CP\CpController;
use Illuminate\Support\Facades\DB;
use Statamic\Facades\Markdown;
use League\HTMLToMarkdown\HtmlConverter;
use Illuminate\Support\Facades\Artisan;

class ConfiguracionesController extends CpController
{
    public function index()
    {
        $this->authorize('view chatbot');
        $configuraciones = DB::table('chatbot_configuraciones')->paginate(25);
        return view('catalog::chatbot.configuraciones.index', compact('configuraciones'));
    }

    public function create()
    {
        $this->authorize('view chatbot');
        return view('catalog::chatbot.configuraciones.create');
    }

    public function store(Request $request)
    {
        $this->authorize('view chatbot');
        $respuesta_html = Markdown::parse($request->valor);
        $respuesta_html = str_replace("href=", 'target="_blank" href=', $respuesta_html);
        DB::table('chatbot_configuraciones')->insert([
            'nombre' => $request->nombre,
            'valor' => $respuesta_html,
            'descripcion' => $request->descripcion,
        ]);
        return redirect()->to(cp_route('chatbot.configuraciones'))->withSuccess('Registro creado');
    }

    public function edit($id)
    {
        $this->authorize('view chatbot');
        $configuracion = DB::table('chatbot_configuraciones')->where('id', $id)->first();
        $converter = new HtmlConverter();
        $valor = $converter->convert($configuracion->valor);

        return view('catalog::chatbot.configuraciones.edit', compact('configuracion', 'valor'));
    }

    public function update(Request $request, $id)
    {
        $this->authorize('view chatbot');
        $respuesta_html = Markdown::parse($request->valor);
        $respuesta_html = str_replace("href=", 'target="_blank" href=', $respuesta_html);
        DB::table('chatbot_configuraciones')->where('id', $id)->update([
            'valor' => $respuesta_html,
        ]);
        return redirect()->to(cp_route('chatbot.configuraciones'))->withSuccess('Registro actualizado');
    }

    public function clearCache()
    {
        $this->authorize('view chatbot');
        Artisan::call('cache:clear');
        return redirect()->to(cp_route('chatbot.configuraciones'))->withSuccess('Configuraciones actualizadas con éxito');
    }
}
