@extends('statamic::layout')
@section('title', 'Periodicidad')

@section('content')
<div class="flex items-center mb-6">
    <h1 class="flex-1">Editar Periodicidades</h1>
    <a href="{{ cp_route('calculadora.periodicidades') }}" class="btn">Regresar</a>
</div>

<hr><br>
<div class="card p-3">
    <form action="{{ cp_route('calculadora.periodicidades.update', $periodicidad->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-4">
            <label for="nombre" class="block text-sm font-medium text-gray-700">Periodicidad</label>
            <input type="text" name="nombre" id="nombre" value="{{ $periodicidad->nombre }}" class="p-2 mt-1 block w-full rounded-md border border-grey-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
        </div>
        <div class="mb-4">
            <label for="dias" class="block text-sm font-medium text-gray-700">Días</label>
            <input type="number" name="dias" id="dias" value="{{ $periodicidad->dias }}" class="p-1 mt-1 block w-full rounded-md border border-grey-100 hadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
        </div>
        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:text-sm">Actualizar</button>
    </form>
</div>
@endsection
