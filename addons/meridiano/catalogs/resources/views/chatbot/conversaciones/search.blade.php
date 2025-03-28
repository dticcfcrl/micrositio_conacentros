@extends('statamic::layout')
@section('title', 'Configuraciones')

@section('content')

    <div>
        <a href="{{ cp_route('chatbot.conversaciones') }}" class="btn">Regresar</a>
    </div>
    <br>
    <div class="flex items-center mb-6">
        <h1 class="flex-1">Conversaciones</h1>
    </div>

    <hr><br>
    <form method="GET" action="{{ cp_route('chatbot.conversaciones.search') }}" class="w-full flex justify-between items-start">
        <div class="flex flex-col w-full p-3">
            <input type="text" name="search"
                class="w-full p-2 mt-1 block rounded-md border border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm mb-2"
                placeholder="Escriba los términos o palabras que desea buscar" value="{{ request('search') }}">
        </div>
        <div class="flex flex-col items-center w-1/5 p-3">
            <button type="submit"
                class="w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mb-2">Buscar</button>
            @if (request('search'))
                <a href="{{ cp_route('chatbot.conversaciones') }}"
                    class="w-full bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">Limpiar</a>
            @endif
        </div>
    </form>
    @if($mensajes->count())
        <div class="card p-2">
            <table class="w-full text-center bg-white">
                <thead>
                    <tr>
                        <th class="py-2 px-4 bg-gray-200 font-bold uppercase text-sm text-gray-600">Sección</th>
                <th class="py-2 px-4 bg-gray-200 font-bold uppercase text-sm text-gray-600">Valor</th>
                <th class="py-2 px-4 bg-gray-200 font-bold uppercase text-sm text-gray-600">Origen</th>
                <th class="py-2 px-4 bg-gray-200 font-bold uppercase text-sm text-gray-600">Respuesta</th>
                <th class="py-2 px-4 bg-gray-200 font-bold uppercase text-sm text-gray-600">ID Conversación</th>
                        <th class="py-2 px-4 bg-gray-200 font-bold uppercase text-sm text-gray-600">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($mensajes as $mensaje)
                        <tr>
                            <td class="py-2 px-4 border-b border-gray-200 max-w-xs">{{ $mensaje->seccion }}</td>
                            <td class="py-2 px-4 border-b border-gray-200 max-w-xs">{{ $mensaje->valor_seccion }}</td>
                            <td class="py-2 px-4 border-b border-gray-200 max-w-xs">{{ $mensaje->origen }}</td>
                            <td class="py-2 px-4 border-b border-gray-200 max-w-xs">{!! $mensaje->respuesta !!}</td>
                            <td class="py-2 px-4 border-b border-gray-200 max-w-xs">{{ $mensaje->id_usuario }}</td>
                            <td class="py-2 px-4 border-b border-gray-200">
                                <a href="{{ cp_route('chatbot.conversaciones.view', $mensaje->id_usuario) }}"
                                    class="btn btn-sm btn-primary"><img src="{{ asset('svg/eye.svg') }}" alt="ver"
                                        class="w-5 h-5"></a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="w-full">
                <div>
                    {{ $mensajes->links('pagination.tailwind') }}
                </div>
            </div>
        </div>
    @endif
@endsection
