@extends('statamic::layout')
@section('title', 'Configuración')

<!-- Include EasyMDE CSS -->
<link rel="stylesheet" href="https://unpkg.com/easymde/dist/easymde.min.css">

<!-- Include EasyMDE JS -->
<script src="https://unpkg.com/easymde/dist/easymde.min.js"></script>
@section('content')
<div class="flex items-center mb-6">
    <h1 class="flex-1">Ver Conversación</h1>
    <a href="{{ url()->previous() }}" class="btn">Regresar</a>
</div>

<hr><br>
<div class="card p-2">
    <div class="p-3 text-center text-gray-700">
        <h2>ID conversación: {{$conversacion[0]->id_usuario}}</h2>
    </div>
    <hr>
    <table class="w-full text-center bg-white">
        <thead>
            <tr>
                <th class="py-2 px-4 bg-gray-200 font-bold uppercase text-sm text-gray-600">Sección</th>
                <th class="py-2 px-4 bg-gray-200 font-bold uppercase text-sm text-gray-600">Valor</th>
                <th class="py-2 px-4 bg-gray-200 font-bold uppercase text-sm text-gray-600">Origen</th>
                <th class="py-2 px-4 bg-gray-200 font-bold uppercase text-sm text-gray-600">Respuesta</th>
                <th class="py-2 px-4 bg-gray-200 font-bold uppercase text-sm text-gray-600">Fecha</th>
            </tr>
        </thead>
        <tbody>
            @foreach($conversacion as $mensaje)
                <tr>
                    <td class="py-2 px-4 border-b border-gray-200 max-w-xs">{{ $mensaje->seccion }}</td>
                    <td class="py-2 px-4 border-b border-gray-200 max-w-xs">{{ $mensaje->valor_seccion }}</td>
                    <td class="py-2 px-4 border-b border-gray-200 max-w-xs">{{ $mensaje->origen }}</td>
                    <td class="py-2 px-4 border-b border-gray-200 max-w-xs">{!! $mensaje->respuesta !!}</td>
                    <td class="py-2 px-4 border-b border-gray-200 max-w-xs">{{ $mensaje->fecha_creacion }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="w-full">
        <div>
            {{ $conversacion->links('pagination.tailwind') }}
        </div>
    </div>
    
</div>
@endsection
