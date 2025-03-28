<?php
namespace Meridiano\Catalogs\Http\Controllers\Chatbot;

use Illuminate\Http\Request;
use Statamic\Http\Controllers\CP\CpController;
use Illuminate\Support\Facades\DB;
use Statamic\Facades\Markdown;
use League\HTMLToMarkdown\HtmlConverter;
use Illuminate\Support\Facades\Log;

class PreguntasController extends CpController
{
    public function index(Request $request)
    {
        $this->authorize('view chatbot');
        $query = DB::table('chatbot_preguntas')
            ->leftJoin('chatbot_pregunta_topico', 'chatbot_preguntas.id', '=', 'chatbot_pregunta_topico.pregunta_id')
            ->leftJoin('chatbot_topicos', 'chatbot_pregunta_topico.topico_id', '=', 'chatbot_topicos.id')
            ->leftJoin('chatbot_pregunta_subtopico', 'chatbot_preguntas.id', '=', 'chatbot_pregunta_subtopico.pregunta_id')
            ->leftJoin('chatbot_subtopicos', 'chatbot_pregunta_subtopico.subtopico_id', '=', 'chatbot_subtopicos.id')
            ->select('chatbot_preguntas.*', 
                    DB::raw('GROUP_CONCAT(DISTINCT chatbot_topicos.nombre ORDER BY chatbot_topicos.nombre ASC) as topicos'), 
                    DB::raw('GROUP_CONCAT(DISTINCT chatbot_subtopicos.nombre ORDER BY chatbot_subtopicos.nombre ASC) as subtopicos'))
            ->groupBy('chatbot_preguntas.id');

        if ($request->filled('search')) {
            $query->where('chatbot_preguntas.pregunta', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('topico_id')) {
            $query->where('chatbot_pregunta_topico.topico_id', $request->topico_id);
        }

        if ($request->filled('subtopico_id')) {
            $query->where('chatbot_pregunta_subtopico.subtopico_id', $request->subtopico_id);
        }

        $preguntas = $query->paginate(25);
        $topicos = DB::table('chatbot_topicos')->get();
        $subtopicos = DB::table('chatbot_subtopicos')->get();

        return view('catalog::chatbot.preguntas.index', compact('preguntas', 'topicos', 'subtopicos'));
    }

    public function create()
    {
        $this->authorize('view chatbot');
        $topicos = DB::table('chatbot_topicos')->get();
        $subtopicos = DB::table('chatbot_subtopicos')->get();
        $preguntas = DB::table('chatbot_preguntas')->get();
        
        return view('catalog::chatbot.preguntas.create', compact('topicos', 'subtopicos', "preguntas"));
    }

    public function store(Request $request)
    {
        $this->authorize('view chatbot');
        $respuesta_html = Markdown::parse($request->respuesta);
        $respuesta_html = str_replace("href=", 'target="_blank" href=', $respuesta_html);
        $preguntas_alternas_procesadas = $request->preguntas_alternas ? array_map([$this, 'cleanText'], $request->preguntas_alternas) : null;

        $pregunta_id = DB::table('chatbot_preguntas')->insertGetId([
            'pregunta' => $request->pregunta,
            'preguntas_procesadas' => $this->cleanText($request->pregunta),
            'respuesta' => $respuesta_html,
            'preguntas_alternas' =>  json_encode($request->preguntas_alternas),
            'preguntas_alternas_procesadas' =>  json_encode($preguntas_alternas_procesadas),
            'palabras_clave' => json_encode($request->palabras_clave),
        ]);

        if ($request->has('topico_id')) {
            foreach ($request->topico_id as $topico_id) {
                DB::table('chatbot_pregunta_topico')->insert([
                    'pregunta_id' => $pregunta_id,
                    'topico_id' => $topico_id,
                ]);
            }
        }

        if ($request->has('subtopico_id')) {
            foreach ($request->subtopico_id as $subtopico_id) {
                DB::table('chatbot_pregunta_subtopico')->insert([
                    'pregunta_id' => $pregunta_id,
                    'subtopico_id' => $subtopico_id,
                ]);
            }
        }

    if ($request->has('preguntas_relacionadas')) {
        foreach ($request->input('preguntas_relacionadas') as $relacionada_id) {
            DB::table('chatbot_pregunta_relacionada')->insert([
                'pregunta_id' => $pregunta_id,
                'relacionada_id' => $relacionada_id,
            ]);
        }
    }

        return redirect()->to(cp_route('chatbot.preguntas'))->withSuccess('Registro creado');
    }

    public function edit($id)
    {
        $this->authorize('view chatbot');
        $pregunta = DB::table('chatbot_preguntas')->where('id', $id)->first();
        $preguntas = DB::table('chatbot_preguntas')->get();
        $topicos = DB::table('chatbot_topicos')->get();
        $subtopicos = DB::table('chatbot_subtopicos')->get();
        $pregunta_topicos = DB::table('chatbot_pregunta_topico')->where('pregunta_id', $id)->pluck('topico_id')->toArray();
        $pregunta_subtopicos = DB::table('chatbot_pregunta_subtopico')->where('pregunta_id', $id)->pluck('subtopico_id')->toArray();
        $pregunta_relacionadas = DB::table('chatbot_pregunta_relacionada')->where('pregunta_id', $id)->pluck('relacionada_id')->toArray();
        $converter = new HtmlConverter();
        $respuesta = $converter->convert($pregunta->respuesta);
        $preguntas_alternas = json_decode($pregunta->preguntas_alternas);
        $palabras_clave = json_decode($pregunta->palabras_clave);
        $sugerencias = $this->obtenerSugerencias($pregunta_topicos, $pregunta_subtopicos, $id);

        return view('catalog::chatbot.preguntas.edit', compact('pregunta', 'preguntas', 'respuesta', 'topicos', 'subtopicos', 'pregunta_topicos', 'pregunta_subtopicos', "preguntas_alternas", "palabras_clave", "pregunta_relacionadas", "sugerencias"));
    }

    public function update(Request $request, $id)
    {
        $this->authorize('view chatbot');
        $respuesta_html = Markdown::parse($request->respuesta);
        $respuesta_html = str_replace("href=", 'target="_blank" href=', $respuesta_html);
        $preguntas_alternas_procesadas = $request->preguntas_alternas ? array_map([$this, 'cleanText'], $request->preguntas_alternas) : null;

        DB::table('chatbot_preguntas')->where('id', $id)->update([
            'pregunta' => $request->pregunta,
            'preguntas_procesadas' => $this->cleanText($request->pregunta),
            'respuesta' => $respuesta_html,
            'preguntas_alternas' =>  json_encode($request->preguntas_alternas),
            'preguntas_alternas_procesadas' =>  json_encode($preguntas_alternas_procesadas),
            'palabras_clave' => json_encode($request->palabras_clave),
        ]);

        DB::table('chatbot_pregunta_topico')->where('pregunta_id', $id)->delete();
        if ($request->has('topico_id')) {
            foreach ($request->topico_id as $topico_id) {
                DB::table('chatbot_pregunta_topico')->insert([
                    'pregunta_id' => $id,
                    'topico_id' => $topico_id,
                ]);
            }
        }

        DB::table('chatbot_pregunta_subtopico')->where('pregunta_id', $id)->delete();
        if ($request->has('subtopico_id')) {
            foreach ($request->subtopico_id as $subtopico_id) {
                DB::table('chatbot_pregunta_subtopico')->insert([
                    'pregunta_id' => $id,
                    'subtopico_id' => $subtopico_id,
                ]);
            }
        }

        DB::table('chatbot_pregunta_relacionada')->where('pregunta_id', $id)->delete();
        if ($request->has('preguntas_relacionadas')) {
            foreach ($request->input('preguntas_relacionadas') as $relacionada_id) {
                DB::table('chatbot_pregunta_relacionada')->insert([
                    'pregunta_id' => $id,
                    'relacionada_id' => $relacionada_id,
                ]);
            }
        }

        return redirect()->to(cp_route('chatbot.preguntas'))->withSuccess('Registro actualizado');
    }

    public function destroy($id)
    {
        DB::table('chatbot_preguntas')->where('id', $id)->delete();
        return redirect()->to(cp_route('chatbot.preguntas'))->withSuccess('Registro eliminado');
    }

    private function cleanText($text) {
        $text = json_decode('"' . $text . '"');
        // Arreglo con stop words
        $stopWords = ["seriamos", "tengamos", "eras", "vuestras", "teniendo", "somos", "contra", "estuvo", "estuvieras", "estado", "estuvisteis", "otro", "erais", "todo", "sera", "tendreis", "los", "sobre", "fue", "hubieran", "tienes", "seran", "estuviera", "suya", "este", "he", "habre", "tuvieramos", "habiais", "habida", "fuimos", "algunas", "tus", "tendriamos", "esteis", "nuestro", "tenidas", "tendria", "suyos", "estais", "ellos", "esos", "hasta", "sentido", "te", "tenga", "mia", "cual", "unos", "tuvieras", "algo", "le", "fuisteis", "esto", "pero", "mi", "seais", "eres", "lo", "tengais", "estuvieramos", "fuiste", "hubiste", "nuestros", "has", "estos", "tengan", "tuviésemos", "estuve", "fueran", "para", "tuya", "tuyos", "fueses", "tenido", "tuvierais", "serian", "habran", "tuvisteis", "habiamos", "sintiendo", "o", "mias", "habia", "fuera", "tienen", "tengas", "desde", "antes", "un", "hayais", "habriamos", "habeis", "vuestra", "habrian", "hayas", "y", "estarian", "la", "sentidas", "tuvo", "hubieseis", "tuviste", "hubieramos", "hube", "estuviese", "sentida", "sentidos", "las", "tuvieran", "sus", "estas", "nos", "estaras", "ha", "tenidos", "nosotras", "habra", "muchos", "tendrian", "hay", "tenida", "nuestra", "tengo", "estarias", "tuvimos", "estuvieran", "habiendo", "estuvierais", "estad", "fuesen", "ni", "mis", "fueras", "tendriais", "mios", "tuvieron", "cuando", "tenia", "vosotras", "tendrias", "tened", "suyas", "al", "estar", "sin", "muy", "tenemos", "estaban", "hubimos", "me", "fuéramos", "hubisteis", "habria", "ante", "suyo", "tambien", "hubiesen", "fuerais", "estan", "hubieras", "estareis", "vuestros", "durante", "estabamos", "con", "donde", "este", "sean", "seas", "habido", "hubieron", "ya", "hubiesemos", "tendre", "esten", "nuestras", "sere", "vuestro", "estuviesen", "hemos", "tendran", "seras", "tenian", "quien", "habias", "era", "estamos", "teniais", "tuyo", "una", "mi", "se", "algunos", "tuyas", "estoy", "siente", "son", "eso", "sea", "seria", "uno", "tendra", "habremos", "habidos", "estes", "hubiera", "otra", "estuvimos", "nosotros", "entre", "soy", "estuviésemos", "haya", "el", "es", "tuviera", "del", "a", "otras", "de", "seremos", "estemos", "teneis", "otros", "poco", "seamos", "eran", "esta", "estas", "sois", "estuvieseis", "tendras", "mucho", "esas", "estariamos", "les", "que", "ella", "vosotros", "tuviese", "porque", "yo", "estaria", "habian", "estare", "hayamos", "tu", "teniamos", "tu", "tendremos", "hubierais", "todos", "nada", "hubieses", "ellas", "hubiese", "esta", "estara", "habreis", "fuesemos", "tuvieses", "su", "os", "estadas", "fuese", "eramos", "por", "estuvieses", "hayan", "mio", "estaremos", "estaran", "fueron", "ese", "sentid", "tiene", "e", "estas", "el", "estabas", "estada", "hubo", "fueseis", "en", "mas", "que", "han", "habras", "tuve", "tanto", "estuviste", "estarias", "tendremos", "estando", "tuviesen", "quienes", "estuvieron", "tenias", "fui", "como", "tuvieseis", "habriais", "si", "estaba", "serias", "ti", "seriais", "sereis", "estabais", "estados", "habidas", "esa", "habrias"];
        $customWords = ["wey", "mano", "eso", "carnal"];
        //Pasar a minusculas
        $text = mb_strtolower($text);
        // Remover puntos de puntuacion
        $text = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $text);
        // Quitar acentos
        $text = preg_replace('/[^\w\s]/', '', $text);

        $words = preg_split("/\s+/", $text);
        $filteredWords = array_filter($words, function($word) use ($stopWords, $customWords) {
            return !in_array($word, $stopWords) && !in_array($word, $customWords);
        });
        
        return implode(" ", $filteredWords);
    }

    private function obtenerSugerencias($topicos = [], $subtopicos = [], $excluirId = null)
    {
        $this->authorize('view chatbot');
        $query = DB::table('chatbot_preguntas')
            ->join('chatbot_pregunta_topico', 'chatbot_preguntas.id', '=', 'chatbot_pregunta_topico.pregunta_id')
            ->join('chatbot_pregunta_subtopico', 'chatbot_preguntas.id', '=', 'chatbot_pregunta_subtopico.pregunta_id')
            ->whereIn('chatbot_pregunta_topico.topico_id', $topicos)
            ->whereIn('chatbot_pregunta_subtopico.subtopico_id', $subtopicos);

        if ($excluirId) {
            $query->where('chatbot_preguntas.id', '!=', $excluirId);
        }

        return $query->select('chatbot_preguntas.id', 'chatbot_preguntas.pregunta')->distinct()->get();
    }

    public function obtenerSugerenciasDinamicas(Request $request)
    {
        $this->authorize('view chatbot');
        $topicos = $request->input('topicos', []);
        $subtopicos = $request->input('subtopicos', []);

        $sugerencias = DB::table('chatbot_preguntas')
            ->join('chatbot_pregunta_topico', 'chatbot_preguntas.id', '=', 'chatbot_pregunta_topico.pregunta_id')
            ->join('chatbot_pregunta_subtopico', 'chatbot_preguntas.id', '=', 'chatbot_pregunta_subtopico.pregunta_id')
            ->whereIn('chatbot_pregunta_topico.topico_id', $topicos)
            ->whereIn('chatbot_pregunta_subtopico.subtopico_id', $subtopicos)
            ->select('chatbot_preguntas.id', 'chatbot_preguntas.pregunta')
            ->distinct()
            ->get();

        return response()->json(['sugerencias' => $sugerencias]);
    }
}
