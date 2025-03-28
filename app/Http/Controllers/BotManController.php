<?php

namespace App\Http\Controllers;

use BotMan\BotMan\BotMan;
use App\Http\Controllers\Conversations\OnboardingConversation;

class BotManController extends Controller
{

    public function handle()
    {
        $botman = app('botman');

        $botman->hears('{message}', function(BotMan $botman, $message) {
            $this->startInitiConversation($botman, $message);
        });

        $botman->listen();
    }

    public function startInitiConversation(BotMan $bot, $message)
    {
        $bot->startConversation(new OnboardingConversation($message));
    }

}