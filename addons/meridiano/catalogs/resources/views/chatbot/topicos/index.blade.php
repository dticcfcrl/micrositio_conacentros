@extends('statamic::layout')
@section('title', 'Tópicos')

@section('content')

<div>
    <a href="{{ cp_route('catalog.chatbot.index') }}" class="btn">Regresar</a>
</div>
<br>
<div class="flex items-center mb-6">
    <h1 class="flex-1">Tópicos</h1>
    <a href="{{ cp_route('chatbot.topicos.create') }}" class="btn-primary">Crear Nuevo Tópico</a>
</div>

<hr><br>
<div class="card p-2">
    <table class="w-full text-center bg-white">
        <thead>
            <tr>
                <th class="py-2 px-4 bg-gray-200 font-bold uppercase text-sm text-gray-600">Nombre</th>
                <th class="py-2 px-4 bg-gray-200 font-bold uppercase text-sm text-gray-600">Descripción</th>
                <th class="py-2 px-4 bg-gray-200 font-bold uppercase text-sm text-gray-600">Orden</th>
                <th class="py-2 px-4 bg-gray-200 font-bold uppercase text-sm text-gray-600">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($topicos as $topico)
            <tr>
                <td class="py-2 px-4 border-b border-gray-200">{{ $topico->nombre }}</td>
                <td class="py-2 px-4 border-b border-gray-200">{{ $topico->descripcion }}</td>
                <td class="py-2 px-4 border-b border-gray-200">{{ $topico->orden }}</td>
                <td class="py-2 px-4 border-b border-gray-200 max-w-xs w-1/4">
                    <a href="{{ cp_route('chatbot.topicos.edit', $topico->id) }}" class="btn btn-sm btn-primary"><img src="{{ asset('svg/edit.svg') }}" alt="editar" class="w-5 h-5"></a>
                    <button type="button" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#deleteModal" data-id="{{ $topico->id }}" onclick="showModal({{ $topico->id }})"><img src="{{ asset('svg/rubbish-bin.svg') }}" alt="Eliminar" class="w-5 h-5"></button>
                </td>                   
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="w-full">
        <div>
            {{ $topicos->links('pagination.tailwind') }}
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
        document.getElementById('deleteForm').action = "{{ cp_route('chatbot.topicos.destroy', '') }}/" + id;
        document.getElementById('deleteModal').classList.remove('hidden');
    }

    function hideModal() {
        document.getElementById('deleteModal').classList.add('hidden');
    }
</script>
@endsection
