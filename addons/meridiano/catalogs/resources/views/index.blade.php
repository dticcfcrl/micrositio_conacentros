@extends('statamic::layout')
@section('title', 'Catálogo')

@section('content')
<h1>Catálogos</h1>
<p>Sección para poder ver, modificar, agregar y eliminar los datos de los catálogos de la base de datos.</p>

<hr>
<br>

<div class="card p-0">
    <table data-size="sm" tabindex="0" class="data-table">
        <thead>
            <tr>
                <th class="group current-column sortable-column"><span>Título</span></th>
                <th class="group sortable-column rtl:text-left ltr:text-right rtl:pl-8 ltr:pr-8"><span>Acceso</span></th>
                <th class="actions-column"></th>
            </tr>
        </thead>
        <tbody tabindex="0">
            @can('view calculadora')
                <tr class="sortable-row outline-none" tabindex="0">
                    <td><a href="{{ cp_route('catalog.calculadora.index') }}">Calculadora</a></td>
                    <td class="rtl:text-left ltr:text-right rtl:pl-8 ltr:pr-8"><div><div handle="entries" values="[object Object]" class="">Acceso permitido</div></div></td>
                </tr>
            @endcan
            @can('view chatbot')
                <tr class="sortable-row outline-none" tabindex="0">
                    <td><a href="{{ cp_route('catalog.chatbot.index') }}">Chatbot</a></td>
                    <td class="rtl:text-left ltr:text-right rtl:pl-8 ltr:pr-8"><div><div handle="entries" values="[object Object]" class="">Acceso permitido</div></div></td>
                </tr>
            @endcan
        </tbody>
    </table>
</div>

@endsection
