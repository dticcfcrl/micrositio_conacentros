<?php

namespace App\Http\Controllers\Conversations;

use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Question;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Conversations\Conversation;
use App\Http\Controllers\Conversations\OpenQuestionConversation;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use League\HTMLToMarkdown\HtmlConverter;
use Carbon\Carbon;

class TopicsConversation extends Conversation
{
    protected $button_open_question;
    protected $topic_id;
    protected $subtopic_id;

    public function __construct($topic_id, $subtopic_id)
    {
        $message = Cache::rememberForever('button_open_question', function () {
            return DB::table('chatbot_configuraciones')
                    ->where('nombre', 'Botón pregunta abierta')
                    ->value('valor');
        });
        $this->button_open_question = Button::create($message)
                                        ->value('PreguntaAbierta')->additionalParameters(["class"=>"openQuestion"]);
        $this->topic_id = $topic_id;
        $this->subtopic_id = $subtopic_id;
    }

    public function run()
    {   
        $this->routesMenu($this->topic_id, $this->subtopic_id);
    }

    private function getTopics() {
        $topics = Cache::rememberForever('topics', function () {
            return DB::table('chatbot_topicos')->orderBy('orden', 'asc')->get();
        });

        return $topics;
    }

    public function principalMenu($param=null)
    {
        $message = Cache::rememberForever('message_principal_menu', function () {
            return DB::table('chatbot_configuraciones')
                    ->where('nombre', 'Mensaje tópicos')
                    ->value('valor');
        });
        $question = Question::create($message);
        
        $topics = $this->getTopics();

        foreach ($topics as $topic) {
            $question->addButton(Button::create($topic->nombre)->value($topic->nombre));
        }
        $question->addButton($this->button_open_question);
        $this->logearConversacion("Menú principal", "Tópicos", "chatbot", $message, $this->bot->getUser());


        $this->ask($question, function (Answer $answer) use ($topics) {
            $this->logearConversacion("Menú principal", "Tópicos", "usuario", $answer->getValue(), $this->bot->getUser());
            // Validamos si la opcion viene del boton o lo escribieron
            if($answer->isInteractiveMessageReply()) {
                // La respuesta viene del boton
                $selectedOption = $answer->getValue();

                foreach ($topics as $topic) {
                    if ($selectedOption === $topic->nombre) {
                        $this->say($topic->nombre, additionalParameters: ["from" => "visitor scroll-message"]);
                        $this->askSubTopics($topic->id);
                    }
                }

                if ($selectedOption === 'PreguntaAbierta') {
                    $this->say("Realizar una pregunta", additionalParameters: ["from" => "visitor scroll-message"]);
                    $this->bot->startConversation(new OpenQuestionConversation(null, null));
                }
            } else {
                // Validamos la respuesta por similitud
                $this->findSimilarTopic($answer->getValue(), $topics);
            }
        });
    }

    private function getSubtopics($topic_id) {
        $subtopics = Cache::rememberForever('topicos_subtopicos', function () {
            return DB::table('chatbot_subtopicos')
            ->join('chatbot_pregunta_subtopico', 'chatbot_subtopicos.id', '=', 'chatbot_pregunta_subtopico.subtopico_id')
            ->join('chatbot_pregunta_topico', 'chatbot_pregunta_subtopico.pregunta_id', '=', 'chatbot_pregunta_topico.pregunta_id')
            ->join('chatbot_topicos', 'chatbot_pregunta_topico.topico_id', '=', 'chatbot_topicos.id')
            ->select('chatbot_subtopicos.id', 'chatbot_subtopicos.nombre', 'chatbot_topicos.id as topico_id')
            ->distinct()
            ->get()
            ->toArray();
        });
        
        // Filtrar subtopicos por el ID del tópico
        $subtopics_by_topic = array_filter($subtopics, function ($item) use ($topic_id) {
            return $item->topico_id == $topic_id;
        });

        return $subtopics_by_topic;
    }

    public function askSubTopics($topic_id)
    {
        $subtopics = $this->getSubtopics($topic_id);
        
        if (count($subtopics) == 1) {
            $questions = $this->getQuestions($topic_id, $subtopics[0]->id);
            $this->askQuestions($questions, $topic_id, $subtopics[0]->id);
        } else {
            $message = Cache::rememberForever('message_secondary_menu', function () {
                return DB::table('chatbot_configuraciones')
                        ->where('nombre', 'Mensaje subtópicos')
                        ->value('valor');
            });
            $question = Question::create($message);
            $this->logearConversacion("Menú secundario", "Subópicos", "chatbot", $message, $this->bot->getUser());


            $text_menu = Cache::rememberForever('button_return_principal', function () {
                return DB::table('chatbot_configuraciones')
                        ->where('nombre', 'Botón regresar a menú principal')
                        ->value('valor');
            });
            $question->addButton(Button::create($text_menu)->value('MenuPrincipal'));
            foreach ($subtopics as $subtopic) {
                $question->addButton(Button::create($subtopic->nombre)->value($subtopic->nombre));
            }
            $question->addButton($this->button_open_question);
            

            $this->ask($question, function (Answer $answer) use ($topic_id, $subtopics, $text_menu) {
                $this->logearConversacion("Menú secundario", "Subópicos", "usuario", $answer->getValue(), $this->bot->getUser());
                // Validamos si la opcion viene del boton o lo escribieron
                if($answer->isInteractiveMessageReply()) {
                    // La respuesta viene del boton
                    $selectedOption =  $answer->getValue();

                    if ($selectedOption === 'MenuPrincipal') {
                        $this->say($text_menu, additionalParameters: ["from" => "visitor scroll-message"]);
                        $this->principalMenu();
                    }

                    foreach ($subtopics as $subtopic) {
                        if ($selectedOption === $subtopic->nombre) {
                            $this->say($subtopic->nombre, additionalParameters: ["from" => "visitor scroll-message"]);
                            $questions = $this->getQuestions($topic_id, $subtopic->id);
                            $this->askQuestions($questions, $topic_id, $subtopic->id);
                        }
                    }
                    
                    if ($selectedOption === 'PreguntaAbierta') {
                        $this->say("Realizar una pregunta", additionalParameters: ["from" => "visitor scroll-message"]);
                        $this->bot->startConversation(new OpenQuestionConversation($topic_id, null));
                    }
                } else {
                    $this->findSimilarSubtopic($answer->getValue(), $subtopics, $topic_id);
                }
            });
        }
    }

    private function getQuestions($topic_id, $subtopic_id) {
        // Guardar en cache todas las preguntas con sus tópicos y sub-tópicos
        $questions = Cache::rememberForever('questions', function () {
            return DB::table('chatbot_preguntas')
                ->join('chatbot_pregunta_topico', 'chatbot_preguntas.id', '=', 'chatbot_pregunta_topico.pregunta_id')
                ->join('chatbot_topicos', 'chatbot_pregunta_topico.topico_id', '=', 'chatbot_topicos.id')
                ->join('chatbot_pregunta_subtopico', 'chatbot_preguntas.id', '=', 'chatbot_pregunta_subtopico.pregunta_id')
                ->join('chatbot_subtopicos', 'chatbot_pregunta_subtopico.subtopico_id', '=', 'chatbot_subtopicos.id')
                ->select('chatbot_preguntas.id as pregunta_id', 'chatbot_preguntas.pregunta', 'chatbot_preguntas.respuesta', 'chatbot_topicos.id as topico_id', 'chatbot_subtopicos.id as subtopico_id')
                ->get()
                ->toArray();
        });

        // Filtrar los resultados en PHP según el ID del tópico y sub-tópico solicitados
        $filteredQuestions = array_filter($questions, function ($item) use ($topic_id, $subtopic_id) {
            return $item->topico_id == $topic_id && $item->subtopico_id == $subtopic_id;
        });

        return $filteredQuestions;
    }

    protected function askQuestions($questions, $topic_id, $subtopic_id) {
        $message = Cache::rememberForever('message_questions_menu', function () {
            return DB::table('chatbot_configuraciones')
                    ->where('nombre', 'Mensaje preguntas')
                    ->value('valor');
        });

        $text_menu = Cache::rememberForever('button_return_secondary', function () {
            return DB::table('chatbot_configuraciones')
                    ->where('nombre', 'Botón regresar a menú anterior')
                    ->value('valor');
        });
        $question = Question::create($message)
            ->addButton(Button::create($text_menu)->value('Menu'));
        $this->logearConversacion("Menú preguntas", "Preguntas", "chatbot", $message, $this->bot->getUser());
        

        foreach ($questions as $question_) {
            $question->addButton(Button::create($question_->pregunta)->value($question_->pregunta));
        }

        // Agregar opcion de pregunta abierta
        $question->addButton($this->button_open_question);

        $this->ask($question, function (Answer $answer) use ($questions, $topic_id, $subtopic_id, $text_menu) {
            $this->logearConversacion("Menú preguntas", "Preguntas", "usuario", $answer->getValue(), $this->bot->getUser());
            $options = [
                "menu" => "Menu",
                "menu principal" => "Menu",
                "hacer pregunta" => "PreguntaAbierta",
                "pregunta" => "PreguntaAbierta",
            ];

            $questions_anwser = [];
            foreach ($questions as $item) {
                $questions_anwser[$item->pregunta] = $item->respuesta;
            }

            $selectedOption = $answer->isInteractiveMessageReply() ? $answer->getValue() :
                                    $this->findSimilarWords($answer->getValue(), $options);

            if ($selectedOption === 'Menu') {
                $this->say($text_menu, additionalParameters: ["from" => "visitor scroll-message"]);
                $this->returnMenu($topic_id, $subtopic_id);

            } elseif($selectedOption === 'PreguntaAbierta') {
                $this->say("Realizar una pregunta", additionalParameters: ["from" => "visitor scroll-message"]);
                $this->bot->startConversation(new OpenQuestionConversation($topic_id, $subtopic_id));

            } elseif(in_array($selectedOption, array_keys($questions_anwser))) {
                // Obtener la respuesta correspondiente
                $response = $questions_anwser[$selectedOption] ?? 'Lo siento, no encontré una respuesta para esa pregunta.';
                $text_response = Cache::rememberForever('message_answer_found', function () {
                    return DB::table('chatbot_configuraciones')
                            ->where('nombre', 'Mensaje respuesta encontrada')
                            ->value('valor');
                });

                if($questions_anwser[$selectedOption] !== null) {
                    $this->logearConversacion("Pregunta", "Respuesta", "chatbot", $response, $this->bot->getUser());

                    $this->say($selectedOption, additionalParameters: ["from" => "visitor scroll-message"]);
                    $this->say('<span class="ask".'.$text_response.'</span> <br> <br> <span class="response">'.$response.'</span>');
                } else {
                    $this->logearConversacion("Pregunta", "Respuesta", "chatbot", $response, $this->bot->getUser());
                    $this->say('<span class="ask">'.$text_response.'</span> <br> <br> <span class="response">'.$response.'</span>', additionalParameters: ["from" => "chatbot scroll-message"]);
                }
                // Llamar de nuevo a la función para permitir seleccionar otra pregunta
                $this->repeat();
            } else {
                $message = Cache::rememberForever('message_error_menu', function () {
                    return DB::table('chatbot_configuraciones')
                            ->where('nombre', 'Mensaje error en opción')
                            ->value('valor');
                });
                $this->logearConversacion("Menú preguntas", "Preguntas", "chatbot", $message, $this->bot->getUser());

                // Si no coincide con ninguna opción, repetir menu
                $this->say($message, additionalParameters: ["from" => "chatbot scroll-message"]);
                $this->repeat();
            }
        });
    }

    private function findSimilarWords($answer, $options) {
        $threshold = 70;

        $maxSimilarity = 0;
        $selectedOption = null;

        foreach ($options as $option => $keyWord) {
            similar_text(strtolower($option), strtolower($answer), $similarity);
            if ($similarity > $maxSimilarity && $similarity >= $threshold) {
                $maxSimilarity = $similarity;
                $selectedOption = $keyWord;
            }
        }

        return $selectedOption;
    }

    protected function findSimilarTopic($answer, $topics) {
        $threshold = 70;

        // Calcular la similitud entre el mensaje del usuario y cada opción disponible
        $options = [
            "hacer pregunta" => ["askOpenQuestion", null],
            "pregunta" => ["askOpenQuestion", null],
            "realizar pregunta" => ["askOpenQuestion", null],
        ];

        foreach ($topics as $topic) {
            $name = $this->cleanText($topic->nombre);
            $name = str_replace("persona", "", $name);
            $options[$name] = ["askSubTopics", $topic->id];
        }

        $maxSimilarity = 0;
        $selectedOption = null;

        foreach ($options as $option => $function) {
            similar_text($this->cleanText($option), strtolower($answer), $similarity);
            if ($similarity > $maxSimilarity && $similarity >= $threshold) {
                $maxSimilarity = $similarity;
                $selectedOption = $function[0];
                $parameter = $function[1];
            }
        }

        // Si se encontró una opción con suficiente similitud, ejecutar la función correspondiente
        if ($selectedOption !== null) {
            $this->$selectedOption($parameter);
        } else {
            $message = Cache::rememberForever('message_error_menu', function () {
                return DB::table('chatbot_configuraciones')
                        ->where('nombre', 'Mensaje error en opción')
                        ->value('valor');
            });
            $this->logearConversacion("Menú principal", "Tópicos", "chatbot", $message, $this->bot->getUser());
            // Si no coincide con ninguna opción, repetir menu
            $this->say($message, additionalParameters: ["from" => "chatbot scroll-message"]);
            $this->principalMenu();
        }
    }

    protected function findSimilarSubtopic($answer, $subtopics, $topic_id) {
        $threshold = 70;

        // Calcular la similitud entre el mensaje del usuario y cada opción disponible
        $options = [
            "menu" => ["principalMenu", null],
            "menu principal" => ["principalMenu", null],
            "regresar" => ["principalMenu", null],
            "hacer pregunta" => ["askOpenQuestion", null],
            "pregunta" => ["askOpenQuestion", null],
            "realizar pregunta" => ["askOpenQuestion", null],
        ];

        foreach ($subtopics as $subtopic) {
            $name = $this->cleanText($subtopic->nombre);
            $options[$name] = ["askQuestions", $subtopic->id];
        }

        $maxSimilarity = 0;
        $selectedOption = null;

        foreach ($options as $option => $function) {
            similar_text($this->cleanText($option), strtolower($answer), $similarity);
            if ($similarity > $maxSimilarity && $similarity >= $threshold) {
                $maxSimilarity = $similarity;
                $selectedOption = $function[0];
                $parameter = $function[1];
            }
        }

        // Si se encontró una opción con suficiente similitud, ejecutar la función correspondiente
        if ($selectedOption !== null) {
            if($selectedOption == "principalMenu") {
                $this->$selectedOption($parameter);
            } else {
                $questions = $this->getQuestions($topic_id, $parameter);
                $this->$selectedOption($questions, $topic_id, $parameter);
            }
        } else {
            $message = Cache::rememberForever('message_error_menu', function () {
                return DB::table('chatbot_configuraciones')
                        ->where('nombre', 'Mensaje error en opción')
                        ->value('valor');
            });

            $this->logearConversacion("Menú secundario", "subtópicos", "chatbot", $message, $this->bot->getUser());
            // Si no coincide con ninguna opción, repetir menu
            $this->say($message, additionalParameters: ["from" => "chatbot scroll-message"]);
            $this->askSubTopics($topic_id);
        }
    }

    private function askOpenQuestion($topic_id=null, $subtopic_id=null){
        $this->bot->startConversation(new OpenQuestionConversation($topic_id, $subtopic_id));
    }

    private function routesMenu($topic_id, $subtopic_id) {
        if($topic_id == null && $subtopic_id == null) {
            $this->principalMenu();
        } elseif($topic_id != null && $subtopic_id == null) {
            $this->askSubTopics($topic_id);
        } elseif($topic_id != null && $subtopic_id != null) {
            $questions = $this->getQuestions($topic_id, $subtopic_id);
            $this->askQuestions($questions, $topic_id, $subtopic_id);
        } else {
            $this->principalMenu();
        }
    }

    private function returnMenu($topic_id, $subtopic_id) {
        if($topic_id != null && $subtopic_id == null) {
            $this->principalMenu();
        } elseif($topic_id != null && $subtopic_id != null) {
            $subtopics = $this->getSubtopics($topic_id);
            if (count($subtopics) == 1) {
                $this->principalMenu();
            } else {
                $this->askSubTopics($topic_id);
            }
        } else {
            $this->principalMenu();
        }
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

    function logearConversacion($seccion, $valor_seccion, $origen, $respuesta, $id_usuario)
    {
        dispatch(function () use ($seccion, $valor_seccion, $origen, $respuesta, $id_usuario) {
            DB::table('chatbot_conversaciones')->insert([
                'seccion' => $seccion,
                'valor_seccion' => $valor_seccion,
                'origen' => $origen,
                'respuesta' => $respuesta,
                'id_usuario' => $id_usuario->getId(),
                'fecha_creacion' => Carbon::now('America/Mexico_City'),
            ]);
        });
    }
}
