<?php

namespace App\Http\Controllers\Conversations;

use BotMan\BotMan\Messages\Conversations\Conversation;
use App\Http\Controllers\Conversations\TopicsConversation;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class OnboardingConversation extends Conversation
{
    protected $name;
    public function __construct($name)
    {
        $this->name = ucwords(strtolower($name));
    }

    public function run()
    {
        $this->getName();
    }
    
    public function getName()
    {
        $this->logearConversacion("Inicio", "Mensaje bienvenida", "usuario", $this->name, $this->bot->getUser());
        $this->bot->userStorage()->save([
            'name' => $this->name,
        ]);

        $intro_message = Cache::rememberForever('message_intro_chatbot', function () {
            return DB::table('chatbot_configuraciones')
                    ->where('nombre', 'Mensaje y saludo con nombre')
                    ->value('valor');
        });

        
        $intro_message = str_replace("\n", "", $intro_message);
        $intro_message = str_replace("{nombre}", $this->name, $intro_message);
        $this->logearConversacion("Inicio", "Mensaje bienvenida", "chatbot", $intro_message, $this->bot->getUser());
        $this->say($intro_message);
        $this->bot->startConversation(new TopicsConversation(null, null));
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
