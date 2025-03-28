<?php

namespace App\Http\Controllers\Conversations;

use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Question;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Conversations\Conversation;
use App\Http\Controllers\Conversations\TopicsConversation;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use League\HTMLToMarkdown\HtmlConverter;
use Carbon\Carbon;

class OpenQuestionConversation extends Conversation
{

    protected $topic_id;
    protected $subtopic_id;

    public function __construct($topic_id, $subtopic_id)
    {
        $this->topic_id = $topic_id;
        $this->subtopic_id = $subtopic_id;
    }
    protected $all_questions = [];
    protected $all_related_questions = [];

    public function run()
    {
        $this->all_questions = Cache::rememberForever('all_questions', function () {
            return DB::table('chatbot_preguntas')
                ->select('id', 'pregunta', 'respuesta', 'preguntas_procesadas', 'preguntas_alternas_procesadas', 'palabras_clave')
                ->get()
                ->toArray();
        });
        $this->all_related_questions = Cache::rememberForever('all_related_questions', function () {
            return DB::table('chatbot_pregunta_relacionada')
                ->select('pregunta_id', 'relacionada_id')
                ->get()
                ->toArray();
        });
        $this->askForOpenQuestion();
    }
    public function askForOpenQuestion()
    {
        $failAttempts = (int)$this->bot->userStorage()->find()->get('failAttempts', 0);

        // Convertir en configuracion. mensaje y num intentos
        $message_failAttempts = Cache::rememberForever('message_failed_attempts', function () {
            return DB::table('chatbot_configuraciones')
                    ->where('nombre', 'Mensaje intentos fallidos')
                    ->value('valor');
        });

        $max_failAttempts = Cache::rememberForever('max_failed_attempts', function () {
            $max_failAttempts = DB::table('chatbot_configuraciones')
                                ->where('nombre', 'Número de intentos fallidos')
                                ->value('valor');

            $converter = new HtmlConverter();
            return intval($converter->convert($max_failAttempts));
        });

        if($failAttempts >= $max_failAttempts) {
            $this->logearConversacion("Pregunta abierta", "Mensaje intentos fallidos", "chatbot", $message_failAttempts, $this->bot->getUser());
            $this->say($message_failAttempts);
        }
        
        $message = Cache::rememberForever('message_open_question', function () {
            return DB::table('chatbot_configuraciones')
                    ->where('nombre', 'Mesaje pregunta abierta')
                    ->value('valor');
        });

        $question = Question::create($message)
            ->fallback('Lo siento 😔, no puedo procesar tu pregunta en este momento.')
            ->addButtons([
                Button::create('Volver al menú anterior')->value('menu'),
            ]);
        $this->logearConversacion("Menú pregunta abierta", "Pregunta abierta", "chatbot", $message, $this->bot->getUser());
        

        $this->ask($question, function (Answer $answer) {
            $this->logearConversacion("Menú pregunta abierta", "Pregunta abierta", "usuario", $answer->getValue(), $this->bot->getUser());

            if ($answer->isInteractiveMessageReply()) {
                if (trim($answer->getValue()) === 'menu') {
                    $this->returnMenu();
                }
            } else {
                // Si el usuario escribe su pregunta, la almacenamos y luego iniciamos la conversación de ayuda
                $userQuestion = $answer->getText();
                // Buscar por preguntas
                $this->findAnswers($userQuestion);
            }
        });
    }

    protected function findAnswers($userQuestion) {
        //// Este apartado solo imprime el resultado

        // Buscar en preguntas formales
        $similar_questions = $this->findFormalQuestion($userQuestion);
        if(count($similar_questions) == 0) {
            // Buscar en preguntas informales
            $similar_questions = $this->findInformalQuestion($userQuestion);
            if(count($similar_questions) == 0) {
                // Sino buscar en palabras clave (Revisar)
                $total_words = str_word_count($userQuestion);
                if($total_words <= 5) {
                    $similar_questions = $this->findKeywords($userQuestion);
                }
            }
        }

        if(count($similar_questions) > 0) {
            // Se encontraton preguntas semejantes
            $this->showSimilarQuestion($similar_questions);

        } else {
            // NO se encontraton preguntas semejantes
            $this->addAttempt();
            $this->addFailAttempt();
            $this->showEmptyResponse();
            $this->askForOpenQuestion();
        }
    }

    protected function showSimilarQuestion($similarQuestion) {
        $message = Cache::rememberForever('message_found_answers', function () {
            return DB::table('chatbot_configuraciones')
                    ->where('nombre', 'Mesaje pregunta abierta encontrada')
                    ->value('valor');
        });

        $question = Question::create($message)
                    ->fallback('Lo siento, no puedo responder a esa pregunta.');
        $this->logearConversacion("Menú preguntas abiertas", "Preguntas abiertas", "chatbot", $message, $this->bot->getUser());

        $question->addButton(Button::create('Volver al menú anterior')->value('menu'));
        foreach (array_keys($similarQuestion) as $question_) {
            $question->addButton(Button::create($question_)->value($question_));
        }
        
        $message_ask_again = Cache::rememberForever('button_other_question', function () {
            return DB::table('chatbot_configuraciones')
                    ->where('nombre', 'Botón realizar otra pregunta')
                    ->value('valor');
        });
        $question->addButton(Button::create($message_ask_again)->value('PreguntaAbierta'));

        $this->ask($question, function (Answer $answer) {
            $this->logearConversacion("Menú preguntas abiertas", "Preguntas abiertas", "usuario", $answer->getValue(), $this->bot->getUser());

            $options = [
                "menu" => "menu",
                "menu principal" => "MenuPrincipal",
                "hacer pregunta" => "PreguntaAbierta",
                "pregunta" => "PreguntaAbierta",
            ];

            if ($answer->isInteractiveMessageReply()) {
                $selected_question = (string) trim($answer->getValue());
                if ($selected_question === 'menu') {
                    $this->returnMenu();

                } elseif($selected_question === 'PreguntaAbierta') {
                    $this->addAttempt();
                    $this->addFailAttempt();
                    $this->say("Realizar una pregunta", additionalParameters: ["from" => "visitor scroll-message"]);
                    $this->askForOpenQuestion();

                } else {
                    // Buscar la respuesta correspondiente a la pregunta seleccionada
                    $response = "";
                    $relevant_questions = [];
                    foreach ($this->all_questions as $question_item) {
                        if (strtolower($question_item->pregunta) === strtolower($selected_question)) {
                            $response = $question_item->respuesta;
                            $relevant_questions = $this->getRelevantQuestions($question_item->id);
                            break;
                        }
                    }
                    $this->addAttempt();
                    $this->say($selected_question, additionalParameters: ["from" => "visitor scroll-message"]);
                    $text_response = Cache::rememberForever('message_answer_found', function () {
                        return DB::table('chatbot_configuraciones')
                                ->where('nombre', 'Mensaje respuesta encontrada')
                                ->value('valor');
                    });

                    $this->logearConversacion("Pregunta abierta", "Respuesta", "chatbot", $response, $this->bot->getUser());
                    $this->say('<span class="ask">'.$text_response.'</span> <br> <br> <span class="response">'.$response.'</span>');
                    if(count($relevant_questions) == 0){
                        $this->askForOpenQuestion();
                    } else {
                        $this->showRelevantQuestion($relevant_questions);
                    }
                }
            } else {
                $selected_question = $this->findSimilarWords(trim($answer->getValue()), $options);
                if ($selected_question === 'menu') {
                    $this->returnMenu();

                } elseif($selected_question === 'PreguntaAbierta') {
                    $this->addAttempt();
                    $this->addFailAttempt();
                    $this->say("Realizar una pregunta", additionalParameters: ["from" => "visitor scroll-message"]);
                    $this->askForOpenQuestion();

                } else {
                    $this->logearConversacion("Menú preguntas abiertas", "Preguntas abiertas", "chatbot", 'Lo siento, la opción seleccionada no coincide con ninguna opción.', $this->bot->getUser());
                    $this->say('Lo siento, la opción seleccionada no coincide con ninguna opción.', additionalParameters: ["from" => "chatbot scroll-message"]);
                    $this->repeat();
                }
            }
        });
    }

    protected function showEmptyResponse() {
        $response = 'Lo siento 😔, no encontré una respuesta para esa pregunta.';
        $this->logearConversacion("Preguntas abiertas", "Pregunta no encontrada", "chatbot", $response, $this->bot->getUser());
        $this->say('<span class="response">'.$response.'</span>');
    }

    protected function showRelevantQuestion($relevant_questions) {
        $this->clearFailAttempt();
        $message = Cache::rememberForever('message_found_answers', function () {
            return DB::table('chatbot_configuraciones')
                    ->where('nombre', 'Mesaje preguntas relacionadas')
                    ->value('valor');
        });

        $question = Question::create($message)
        ->fallback('Lo siento, no puedo responder a esa pregunta.');
        $this->logearConversacion("Preguntas abiertas", "Preguntas relevantes", "chatbot", $message, $this->bot->getUser());

        $max_relevantQuestions = Cache::rememberForever('max_relevant_questions', function () {
            $max_questions = DB::table('chatbot_configuraciones')
                                ->where('nombre', 'Número de preguntas relevantes')
                                ->value('valor');

            $converter = new HtmlConverter();
            return intval($converter->convert($max_questions));
        });
        if(count($relevant_questions) > $max_relevantQuestions){
            $relevant_questions = array_slice($relevant_questions, 0, $max_relevantQuestions, true);
        }

        $question->addButton(Button::create('Volver al menú anterior')->value('menu'));
        foreach ($relevant_questions as $relevant) {
            $question->addButton(Button::create($relevant)->value($relevant));
        }
        
        $message_ask_again = Cache::rememberForever('button_other_question', function () {
            return DB::table('chatbot_configuraciones')
                    ->where('nombre', 'Botón realizar otra pregunta')
                    ->value('valor');
        });
        $question->addButton(Button::create($message_ask_again)->value('PreguntaAbierta'));
        $this->ask($question, function (Answer $answer) {
            $this->logearConversacion("Menú preguntas relacionadas", "Preguntas relacionadas", "usuario", $answer->getValue(), $this->bot->getUser());

            $options = [
                "menu" => "menu",
                "menu principal" => "MenuPrincipal",
                "hacer pregunta" => "PreguntaAbierta",
                "pregunta" => "PreguntaAbierta",
            ];

            if ($answer->isInteractiveMessageReply()) {
                $selected_question = (string) trim($answer->getValue());
                if ($selected_question === 'menu') {
                    $this->returnMenu();

                } elseif($selected_question === 'PreguntaAbierta') {
                    $this->addAttempt();
                    $this->addFailAttempt();
                    $this->say("Realizar una pregunta", additionalParameters: ["from" => "visitor scroll-message"]);
                    $this->askForOpenQuestion();

                } else {
                    // Buscar la respuesta correspondiente a la pregunta seleccionada
                    $response = "";
                    $relevant_questions = [];
                    foreach ($this->all_questions as $question_item) {
                        if (strtolower($question_item->pregunta) === strtolower($selected_question)) {
                            $response = $question_item->respuesta;
                            $relevant_questions = $this->getRelevantQuestions($question_item->id);
                            break;
                        }
                    }

                    $text_response = Cache::rememberForever('message_answer_found', function () {
                        return DB::table('chatbot_configuraciones')
                                ->where('nombre', 'Mensaje respuesta encontrada')
                                ->value('valor');
                    });

                    $this->addAttempt();
                    $this->logearConversacion("Preguntas relacionadas", "Respuesta", "chatbot", $response, $this->bot->getUser());
                    $this->say($selected_question, additionalParameters: ["from" => "visitor scroll-message"]);
                    $this->say('<span class="ask">'.$text_response.'</span> <br> <br> <span class="response">'.$response.'</span>');
                    if(count($relevant_questions) == 0){
                        $this->askForOpenQuestion();
                    } else {
                        $this->showRelevantQuestion($relevant_questions);
                    }
                }
            } else {
                $selected_question = $this->findSimilarWords(trim(trim($answer->getValue())), $options);
                if ($selected_question === 'menu') {
                    $this->returnMenu();

                } elseif($selected_question === 'PreguntaAbierta') {
                    $this->addAttempt();
                    $this->addFailAttempt();
                    $this->say("Realizar una pregunta", additionalParameters: ["from" => "visitor scroll-message"]);
                    $this->askForOpenQuestion();

                } else {
                    $this->logearConversacion("Menú preguntas relacionadas", "Preguntas relacionadas", "chatbot", 'Lo siento, la opción seleccionada no coincide con ninguna opción.', $this->bot->getUser());
                    $this->say('Lo siento, la opción seleccionada no coincide con ninguna opción.', additionalParameters: ["from" => "chatbot scroll-message"]);
                    $this->repeat();
                }
            }
        });
    }


    private function returnMenu(){
        $this->clearAttempt();
        $this->say("Volver al menú anterior", additionalParameters: ["from" => "visitor scroll-message"]);
        $this->bot->startConversation(new TopicsConversation($this->topic_id, $this->subtopic_id));
    }

    private function getRelevantQuestions($question_id) {
        $relatedIds = array_map(function ($relation) use ($question_id) {
            return $relation->relacionada_id;
        }, array_filter($this->all_related_questions, function ($relation) use ($question_id) {
            return $relation->pregunta_id == $question_id;
        }));

        $relatedQuestions = array_filter($this->all_questions, function ($question) use ($relatedIds) {
            return in_array($question->id, $relatedIds);
        });

        $relatedQuestions = array_map(function ($question) {
            return $question->pregunta;
        }, $relatedQuestions);
    
        return $relatedQuestions;
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

    protected function findFormalQuestion($userQuestion) {
        $thresholds = [65, 60, 55];

        foreach($thresholds as $threshold){
            $questions_formal = array_column($this->all_questions, 'pregunta');
            $similar_options = $this->getSimilarityScores($questions_formal, $userQuestion);

            // Ordenar preguntas por similitud de mayor a menor
            arsort($similar_options);

            $max_questions = Cache::rememberForever('max_show_questions', function () {
                $max_questions = DB::table('chatbot_configuraciones')
                        ->where('nombre', 'Número de preguntas encontradas')
                        ->value('valor');

                $converter = new HtmlConverter();
                return intval($converter->convert($max_questions));
            });

            $similar_questions = $this->excludeBelow($similar_options, $threshold);
            if(count($similar_questions) > $max_questions) {
                $similar_questions = array_slice($similar_questions, 0, $max_questions, true);
                break;
            }
        }

        return $similar_questions;
    }

    protected function findInformalQuestion($userQuestion) {
        $thresholds = [65, 60, 55];
        $similar_questions = [];
        
        foreach($thresholds as $threshold){
            // Reccorrer las preguntas
            foreach($this->all_questions as $question) {
                // Recorrer y sacar similitudes con las preguntas coloquiales
                $similar_options = $this->getSimilarityScores(json_decode($question->preguntas_alternas_procesadas, true), $userQuestion);
                // Obtener maximo de todas las opciones
                $similar_questions[$question->pregunta] = count($similar_options) > 0 ? max($similar_options) : 0;
            }

            // Ordenar preguntas por similitud de mayor a menor
            arsort($similar_questions);

            $max_questions = Cache::rememberForever('max_show_questions', function () {
                $max_questions = DB::table('chatbot_configuraciones')
                        ->where('nombre', 'Número de preguntas encontradas')
                        ->value('valor');

                $converter = new HtmlConverter();
                return intval($converter->convert($max_questions));
            });
            
            $similar_questions = $this->excludeBelow($similar_questions, $threshold);
            if(count($similar_questions) > $max_questions) {
                $similar_questions = array_slice($similar_questions, 0, $max_questions, true);
                break;
            }
        }

        return $similar_questions;
    }

    protected function findKeywords($userQuestion) {
        $thresholds = [90, 80, 70];

        foreach($thresholds as $threshold){
            // Reccorrer las preguntas
            foreach($this->all_questions as $question) {
                // Recorrer y sacar similitudes con las preguntas coloquiales
                
                if($question->palabras_clave != null & $question->palabras_clave != "null") {
                    if(count(json_decode($question->palabras_clave)) > 0) {
                        $similar_options = $this->getSimilarityScores(json_decode($question->palabras_clave), $userQuestion);
                        // Obtener maximo de todas las opciones
                        $similar_questions[$question->pregunta] = count($similar_options) > 0 ? max($similar_options) : 0;
                    }
                }
            }

            // Ordenar preguntas por similitud de mayor a menor
            arsort($similar_questions);

            $max_questions = Cache::rememberForever('max_show_questions', function () {
                $max_questions = DB::table('chatbot_configuraciones')
                        ->where('nombre', 'Número de preguntas encontradas')
                        ->value('valor');

                $converter = new HtmlConverter();
                return intval($converter->convert($max_questions));
            });
            
            $similar_questions = $this->excludeBelow($similar_questions, $threshold);
            if(count($similar_questions) > $max_questions) {
                $similar_questions = array_slice($similar_questions, 0, $max_questions, true);
                break;
            }
        }

        return $similar_questions;
    }

    private function getSimilarityScores($questions, $userQuestion) {
        $similarity_scores = [];

        if($questions){
            $text_for_compare = $this->cleanText($userQuestion);
            foreach($questions as $key => $question){
                $text_to_compare = $this->cleanText($question);
                similar_text($text_to_compare, $text_for_compare, $similarity);
                $similarity_scores[$question] = $similarity;
            }
        }
        
        return $similarity_scores;
    }

    private function excludeBelow($array, $minValue) {
        // Excluir todos los valores debajo al valor minimo
        return array_filter($array, function($value) use ($minValue) {
            return $value >= $minValue;
        });
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

    private function addAttempt(){
        $attempts = (int)$this->bot->userStorage()->find()->get('attempts', 0);
        $this->bot->userStorage()->save([
            'attempts' => $attempts + 1,
        ]);
    }

    private function clearAttempt(){
        $this->bot->userStorage()->save([
            'attempts' => 0,
        ]);

        $this->bot->userStorage()->save([
            'failAttempts' => 0,
        ]);
    }

    private function clearFailAttempt(){
        $this->bot->userStorage()->save([
            'failAttempts' => 0,
        ]);
    }

    private function addFailAttempt(){
        $attempts = (int)$this->bot->userStorage()->find()->get('failAttempts', 0);
        $this->bot->userStorage()->save([
            'failAttempts' => $attempts + 1,
        ]);
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
