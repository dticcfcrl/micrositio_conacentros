@extends('statamic::layout')
@section('title', 'Configuraciones')

@section('content')

<div>
    <a href="{{ cp_route('catalog.chatbot.index') }}" class="btn">Regresar</a>
</div>
<br>
<div class="flex items-center mb-6">
    <h1 class="flex-1">Configuraciones</h1>
    <a href="{{ cp_route('chatbot.configuraciones.create') }}" class="btn-primary">Crear Nueva Configuración</a>
    <a href="{{ cp_route('chatbot.configuraciones.cache') }}" class="btn ml-5">Aplicar configuraciones</a>
</div>

<hr><br>
<div class="card p-2">
    <table class="w-full text-center bg-white">
        <thead>
            <tr>
                <th class="py-2 px-4 bg-gray-200 font-bold uppercase text-sm text-gray-600">Nombre</th>
                <th class="py-2 px-4 bg-gray-200 font-bold uppercase text-sm text-gray-600 max-w-xs w-2/4">Descripción</th>
                <th class="py-2 px-4 bg-gray-200 font-bold uppercase text-sm text-gray-600">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($configuraciones as $configuracion)
                <tr>
                    <td class="py-2 px-4 border-b border-gray-200 max-w-xs w-1/4">{{ $configuracion->nombre }}</td>
                    <td class="py-2 px-4 border-b border-gray-200 max-w-xs w-2/4">{{ $configuracion->descripcion }}</td>
                    <td class="py-2 px-4 border-b border-gray-200 max-w-xs w-1/4">
                        <a href="{{ cp_route('chatbot.configuraciones.edit', $configuracion->id) }}" class="btn btn-sm btn-primary"><img src="{{ asset('svg/edit.svg') }}" alt="editar" class="w-5 h-5"></a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="w-full">
        <div>
            {{ $configuraciones->links('pagination.tailwind') }}
        </div>
    </div>
    
</div>
@endsection
