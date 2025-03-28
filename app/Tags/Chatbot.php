<?php

namespace App\Tags;

use Statamic\Tags\Tags;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use League\HTMLToMarkdown\HtmlConverter;

class Chatbot extends Tags
{
    /**
     * The {{ chatbot:startMessage }} tag.
     *
     * @return string
     */
    public function startMessage()
    {
        $message = Cache::rememberForever('message_start_chatbot', function () {
            return DB::table('chatbot_configuraciones')
                    ->where('nombre', 'Mensaje bienvenida')
                    ->value('valor');
        });

        $message = str_replace("\n", "", $message);

        return $message;
    }

    /**
     * The {{ chatbot:title }} tag.
     *
     * @return string
     */
    public function title()
    {
        $message = Cache::rememberForever('title_start_chatbot', function () {
            return DB::table('chatbot_configuraciones')
                    ->where('nombre', 'Título del chatbot')
                    ->value('valor');
        });

        $converter = new HtmlConverter();
        $message = $converter->convert($message);
        $message = str_replace("\n", "", $message);

        return $message;
    }

    /**
     * The {{ chatbot:placeholder }} tag.
     *
     * @return string
     */
    public function placeholder()
    {
        $message = Cache::rememberForever('placeholder_start_chatbot', function () {
            return DB::table('chatbot_configuraciones')
                    ->where('nombre', 'Texto de referencia')
                    ->value('valor');
        });

        $converter = new HtmlConverter();
        $message = $converter->convert($message);
        $message = str_replace("\n", "", $message);

        return $message;
    }
}
