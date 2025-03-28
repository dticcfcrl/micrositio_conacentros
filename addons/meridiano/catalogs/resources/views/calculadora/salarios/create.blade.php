@extends('statamic::layout')
@section('title', 'Salario')

@section('content')
<div class="flex items-center mb-6">
    <h1 class="flex-1">Crear Salario</h1>
    <a href="{{ cp_route('calculadora.salarios') }}" class="btn">Regresar</a>
</div>

<hr><br>
<div class="card p-3">
    <form action="{{ cp_route('calculadora.salarios.store') }}" method="POST" class="max-w-lg mx-auto bg-white p-8 rounded-lg shadow-md">
        @csrf
        <div class="mb-4">
            <label for="id_profesion" class="block text-sm font-medium text-gray-700">Profesión</label>
            <select name="id_profesion" id="id_profesion" class="p-2 mt-1 block w-full rounded-md border border-grey-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                @foreach($profesiones as $profesion)
                    <option value="{{ $profesion->id }}">{{ $profesion->profesion }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-4">
            <label for="salario" class="block text-sm font-medium text-gray-700">Salario</label>
            <input type="number" step="0.01" name="salario" id="salario" class="p-2 mt-1 block w-full rounded-md border border-grey-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
        </div>
        <div class="mb-4">
            <label for="zona" class="block text-sm font-medium text-gray-700">Zona</label>
            <select name="zona" id="zona" class="p-2 mt-1 block w-full rounded-md border border-grey-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                <option value="general">General</option>
                <option value="frontera norte">Frontera Norte</option>
            </select>
        </div>
        <div class="mb-4">
            <label for="anio" class="block text-sm font-medium text-gray-700">Año</label>
            <input type="number" name="anio" id="anio" class="p-2 mt-1 block w-full rounded-md border border-grey-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
        </div>
        <div class="flex justify-between">
            <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:text-sm">Guardar</button>        </div>
    </form>    
</div>
@endsection
