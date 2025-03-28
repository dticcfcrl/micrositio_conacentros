<?php

namespace Meridiano\Catalogs\Http\Controllers;

use Illuminate\Support\Facades\Artisan;
use Statamic\Http\Controllers\CP\CpController;

class ChatbotController extends CpController
{
    public function index()
    {
        $this->authorize('view chatbot');

        return view('catalog::chatbot.index');
    }

    public function clearCache()
    {
        $this->authorize('view chatbot');
        Artisan::call('cache:clear');
        return redirect()->to(cp_route('catalog.chatbot.index'))->withSuccess('Chatbot actualizado');
    }
}
