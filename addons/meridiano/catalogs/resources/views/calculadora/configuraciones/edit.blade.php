@extends('statamic::layout')
@section('title', 'Configuración')

@section('content')
<div class="flex items-center mb-6">
    <h1 class="flex-1">Editar Configuración</h1>
    <a href="{{ cp_route('calculadora.configuraciones') }}" class="btn">Regresar</a>
</div>

<hr><br>
<div class="card p-3">
    <form action="{{ cp_route('calculadora.configuraciones.update', $configuracion->id) }}" method="POST" class="max-w-lg mx-auto bg-white p-8 rounded-lg shadow-md">
        @csrf
        @method('PUT')
        <div class="mb-4">
            <label for="nombre" class="block text-sm font-medium text-gray-700">Nombre</label>
            <input type="text" name="nombre" id="nombre" value="{{ $configuracion->nombre }}" class="p-2 mt-1 block w-full rounded-md border border-grey-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" disabled>
        </div>
        <div class="mb-4">
            <label for="valor" class="block text-sm font-medium text-gray-700">Valor</label>
            <input type="text" name="valor" id="valor" value="{{ $configuracion->valor }}" class="p-2 mt-1 block w-full rounded-md border border-grey-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
        </div>
        <div class="mb-4">
            <label for="descripcion" class="block text-sm font-medium text-gray-700">Descripción</label>
            <textarea name="descripcion" id="descripcion" rows="4" class="p-2 mt-1 block w-full rounded-md border border-grey-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" disabled>{{ $configuracion->descripcion }}</textarea>
        </div>
        <div class="flex justify-between">
            <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:text-sm">Actualizar</button>
        </div>
    </form>
</div>
@endsection
