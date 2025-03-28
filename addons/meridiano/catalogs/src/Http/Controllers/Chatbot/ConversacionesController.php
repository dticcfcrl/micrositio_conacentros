<?php
namespace Meridiano\Catalogs\Http\Controllers\Chatbot;

use Illuminate\Http\Request;
use Statamic\Http\Controllers\CP\CpController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class ConversacionesController extends CpController
{
    public function index(Request $request)
    {
        $this->authorize('view chatbot');
        $query = DB::table('chatbot_conversaciones')
                    ->select('respuesta', 'id_usuario', 'id')
                    ->whereIn('id', function($subQuery) {
                        $subQuery->select(DB::raw('MIN(id)'))
                            ->from('chatbot_conversaciones')
                            ->groupBy('id_usuario');
                    });

        $searchTerm = $request->search;
        if ($request->filled('search')) {
            $query->where(function($query) use ($searchTerm) {
                $query->where('id_usuario', 'like', "%{$searchTerm}%")
                        ->orWhere('respuesta', 'like', "%{$searchTerm}%");
            });
        }

        $query->orderBy('fecha_creacion', 'desc');
        $mensajes = $query->paginate(25);

        return view('catalog::chatbot.conversaciones.index', compact('mensajes'));
    }

    public function view($id)
    {
        $this->authorize('view chatbot');
        $conversacion = DB::table('chatbot_conversaciones')
                        ->where('id_usuario', $id)
                        ->orderBy('fecha_creacion', 'asc')
                        ->paginate(25);

        return view('catalog::chatbot.conversaciones.view', compact('conversacion'));
    }

    public function search(Request $request)
    {
        $this->authorize('view chatbot');

        if (empty($request->search)) {
            $mensajes = collect([]);
            return view('catalog::chatbot.conversaciones.search', compact('mensajes'));
        }

        $searchTerm = '%'.$request->search.'%';
        $mensajes = DB::table('chatbot_conversaciones')
            ->where('respuesta', 'like', $searchTerm)
            ->orderBy('fecha_creacion', 'desc')
            ->paginate(25);

        return view('catalog::chatbot.conversaciones.search', compact('mensajes'));
    }

    public function download()
    {
        $mensajes = DB::table('chatbot_conversaciones')
            ->select('id', 'seccion', 'valor_seccion', 'origen', 'respuesta', 'id_usuario', 'fecha_creacion')
            ->orderBy('fecha_creacion', 'desc')
            ->get();

        foreach ($mensajes as $mensaje) {
            $csvData[] = [
                $mensaje->id,
                $mensaje->seccion,
                $mensaje->valor_seccion,
                $mensaje->origen,
                $mensaje->respuesta,
                $mensaje->id_usuario,
                $mensaje->fecha_creacion
            ];
        }

        $filename = "conversaciones.csv";
        $handle = fopen($filename, 'w+');
        fputcsv($handle, ['id', 'seccion', 'valor_seccion', 'origen', 'respuesta', 'id_conversacion', 'fecha_creacion']);

        foreach ($csvData as $row) {
            fputcsv($handle, $row);
        }

        fclose($handle);

        $headers = [
            'Content-Type' => 'text/csv',
        ];

        return Response::download($filename, $filename, $headers)->deleteFileAfterSend(true);
    }
}
