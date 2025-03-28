@extends('statamic::layout')
@section('title', 'Chatbot')

@section('content')
<h1>Catálogos de chatbot</h1>
<p>Contenido para las preeguntas y categorías para el flujo Chatbot.</p>

<hr><br>
<div class="p-2 text-center">
    <a href="{{ cp_route('chatbot.cache') }}" class="btn">Actualizar información de chatbot</a>
</div>
<div class="card p-0">
    <table data-size="sm" tabindex="0" class="data-table">
        <thead>
            <tr>
                <th class="group current-column sortable-column"><span>Título</span></th>
                <th class="group sortable-column rtl:text-left ltr:text-right rtl:pl-8 ltr:pr-8"><span>Descripción</span></th>
            </tr>
        </thead>
        <tbody tabindex="0">
            <tr class="sortable-row outline-none" tabindex="0">
                <td><a href="{{ cp_route('chatbot.topicos') }}">Tópicos</a></td>
                <td class="rtl:text-left ltr:text-right rtl:pl-8 ltr:pr-8"><div><div handle="entries">Lista de categorías principales</div></div></td>
            </tr>
            <tr class="sortable-row outline-none" tabindex="0">
                <td><a href="{{ cp_route('chatbot.subtopicos') }}">Subtópicos</a></td>
                <td class="rtl:text-left ltr:text-right rtl:pl-8 ltr:pr-8"><div><div handle="entries">Lista de categorías secundarias</div></div></td>
            </tr>
            <tr class="sortable-row outline-none" tabindex="0">
                <td><a href="{{ cp_route('chatbot.preguntas') }}">Preguntas</a></td>
                <td class="rtl:text-left ltr:text-right rtl:pl-8 ltr:pr-8"><div><div handle="entries">Lista de preguntas</div></div></td>
            </tr>
            <tr class="sortable-row outline-none" tabindex="0">
                <td><a href="{{ cp_route('chatbot.configuraciones') }}">Configuraciones</a></td>
                <td class="rtl:text-left ltr:text-right rtl:pl-8 ltr:pr-8"><div><div handle="entries">Personalización de mensajes y comportamiento del chatbot</div></div></td>
            </tr>
            <tr class="sortable-row outline-none" tabindex="0">
                <td><a href="{{ cp_route('chatbot.conversaciones') }}">Conversaciones</a></td>
                <td class="rtl:text-left ltr:text-right rtl:pl-8 ltr:pr-8"><div><div handle="entries">Interacciones y mensajes del chatbot</div></div></td>
            </tr>
        </tbody>
    </table>
</div>
@endsection
