@extends('statamic::layout')
@section('title', 'Preguntas')

@section('content')

<div>
    <a href="{{ cp_route('catalog.chatbot.index') }}" class="btn">Regresar</a>
</div>
<br>
<div class="flex items-center mb-6">
    <h1 class="flex-1">Preguntas</h1>
    <a href="{{ cp_route('chatbot.preguntas.create') }}" class="btn-primary">Crear Nueva Pregunta</a>
</div>

<hr><br>
<!-- Buscador -->
<form method="GET" action="{{ cp_route('chatbot.preguntas') }}" class="w-full flex justify-between items-start">
    <div class="flex flex-col w-full p-3">
        <input type="text" name="search" class="w-full p-2 mt-1 block rounded-md border border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm mb-2" placeholder="Escriba la pregunta que desea buscar (opcional)" value="{{ request('search') }}">
        <div class="flex space-x-2">
            <select name="topico_id" class="p-2 mt-1 w-1/2 bg-white rounded-md border border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                <option value="">Todos los tópicos</option>
                @foreach($topicos as $topico)
                    <option value="{{ $topico->id }}" {{ request('topico_id') == $topico->id ? 'selected' : '' }}>{{ $topico->nombre }}</option>
                @endforeach
            </select>
            <select name="subtopico_id" class="p-2 mt-1 w-1/2 bg-white rounded-md border border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                <option value="">Todos los subtópicos</option>
                @foreach($subtopicos as $subtopico)
                    <option value="{{ $subtopico->id }}" {{ request('subtopico_id') == $subtopico->id ? 'selected' : '' }}>{{ $subtopico->nombre }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="flex flex-col items-center w-1/5 p-3">
        <button type="submit" class="w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mb-2">Buscar</button>
        @if(request('search') || request('topico_id') || request('subtopico_id'))
            <a href="{{ cp_route('chatbot.preguntas') }}" class="w-full bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">Limpiar</a>
        @endif
    </div>
</form>
<div class="card p-2">
    <table class="w-full text-center bg-white">
        <thead>
            <tr>
                <th class="py-2 px-4 bg-gray-200 font-bold uppercase text-sm text-gray-600">Pregunta</th>
                <th class="py-2 px-4 bg-gray-200 font-bold uppercase text-sm text-gray-600">Tópicos</th>
                <th class="py-2 px-4 bg-gray-200 font-bold uppercase text-sm text-gray-600">Subtópicos</th>
                <th class="py-2 px-4 bg-gray-200 font-bold uppercase text-sm text-gray-600">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($preguntas as $pregunta)
            <tr>
                <td class="py-2 px-4 border-b border-gray-200">{{ $pregunta->pregunta }}</td>
                <td class="py-2 px-4 border-b border-gray-200">{{ $pregunta->topicos }}</td>
                <td class="py-2 px-4 border-b border-gray-200">{{ $pregunta->subtopicos }}</td>
                <td class="py-2 px-4 border-b border-gray-200 w-1/4">
                    <a href="{{ cp_route('chatbot.preguntas.edit', $pregunta->id) }}" class="btn btn-sm btn-primary"><img src="{{ asset('svg/edit.svg') }}" alt="editar" class="w-5 h-5"></a>
                    <button type="button" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#deleteModal" data-id="{{ $pregunta->id }}" onclick="showModal({{ $pregunta->id }})"><img src="{{ asset('svg/rubbish-bin.svg') }}" alt="Eliminar" class="w-5 h-5"></button>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <div class="w-full">
        <div>
            {{ $preguntas->links('pagination.tailwind') }}
        </div>
    </div>
    
</div>

<!-- Modal -->
<div id="deleteModal" class="hidden fixed z-10 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>

    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg p-3">
            <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                        <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                        </svg>
                    </div>
                    <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                        <h3 class="text-base font-semibold leading-6 text-gray-900" id="modal-title">Confirmar Eliminación</h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500">¿Estás seguro de que deseas eliminar este elemento?</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="">
                <button type="button" class="btn inline-flex" onclick="hideModal()">Cancelar</button>
                <form id="deleteForm" action="" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger inline-flex">Eliminar</button>
                </form>
            </div>
        </div>
    </div>
</div>


<script>
    function showModal(id) {
        document.getElementById('deleteForm').action = "{{ cp_route('chatbot.preguntas.destroy', '') }}/" + id;
        document.getElementById('deleteModal').classList.remove('hidden');
    }

    function hideModal() {
        document.getElementById('deleteModal').classList.add('hidden');
    }
</script>
@endsection
