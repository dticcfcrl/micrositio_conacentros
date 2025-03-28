@extends('statamic::layout')
@section('title', 'Periodicidades')

@section('content')

<div>
    <a href="{{ cp_route('catalog.calculadora.index') }}" class="btn">Regresar</a>
</div>
<br>
<div class="flex items-center mb-6">
    <h1 class="flex-1">Periodicidades</h1>
    <a href="{{ cp_route('calculadora.periodicidades.create') }}" class="btn-primary">Crear Nueva Periodicidad</a>
</div>

<hr><br>
<div class="card p-0">
    <table data-size="sm" tabindex="0" class="data-table">
        <thead>
            <tr>
                <th>Periodicidad</th>
                <th>Días</th>
                <th class="text-center">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($periodicidades as $periodicidad)
                <tr class="sortable-row outline-none" tabindex="0">
                    <td>{{ $periodicidad->nombre }}</td>
                    <td>{{ $periodicidad->dias }}</td>
                    <td class="text-center">
                        <a href="{{ cp_route('calculadora.periodicidades.edit', $periodicidad->id) }}" class="btn btn-sm btn-primary"><img src="{{ asset('svg/edit.svg') }}" alt="editar" class="w-5 h-5"></a>
                        <button type="button" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#deleteModal" data-id="{{ $periodicidad->id }}" onclick="showModal({{ $periodicidad->id }})"><img src="{{ asset('svg/rubbish-bin.svg') }}" alt="Eliminar" class="w-5 h-5"></button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
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
        document.getElementById('deleteForm').action = "{{ cp_route('calculadora.periodicidades.destroy', '') }}/" + id;
        document.getElementById('deleteModal').classList.remove('hidden');
    }

    function hideModal() {
        document.getElementById('deleteModal').classList.add('hidden');
    }
</script>
@endsection
