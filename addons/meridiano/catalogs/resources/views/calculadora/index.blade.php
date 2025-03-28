@extends('statamic::layout')
@section('title', 'Calculadora')

@section('content')
<h1>Catálogos de calculadora</h1>
<p>Contenido de la sección Calculadora sobre los elementos que utiliza para realizar los cálculos.</p>

<hr><br>
<div class="card p-0">
    <table data-size="sm" tabindex="0" class="data-table">
        <thead>
            <tr>
                <th class="group current-column sortable-column"><span>Título</span></th>
                <th class="group sortable-column rtl:text-left ltr:text-right rtl:pl-8 ltr:pr-8"><span>Descripción</span></th>
            </tr>
        </thead>
        <tbody tabindex="0">
            <tr class="sortable-row outline-none" tabindex="0">
                <td><a href="{{ cp_route('calculadora.configuraciones') }}">Configuraciones</a></td>
                <td class="rtl:text-left ltr:text-right rtl:pl-8 ltr:pr-8"><div><div handle="entries">Lista de configuraciones</div></div></td>
            </tr>
            <tr class="sortable-row outline-none" tabindex="0">
                <td><a href="{{ cp_route('calculadora.profesiones') }}">Oficios</a></td>
                <td class="rtl:text-left ltr:text-right rtl:pl-8 ltr:pr-8"><div><div handle="entries">Lista de profesiones disponibles</div></div></td>
            </tr>
            <tr class="sortable-row outline-none" tabindex="0">
                <td><a href="{{ cp_route('calculadora.periodicidades') }}">Periodicidades</a></td>
                <td class="rtl:text-left ltr:text-right rtl:pl-8 ltr:pr-8"><div><div handle="entries">Lista de periodicidad de pago disponibles</div></div></td>
            </tr>
            <tr class="sortable-row outline-none" tabindex="0">
                <td><a href="{{ cp_route('calculadora.salarios') }}">Salarios</a></td>
                <td class="rtl:text-left ltr:text-right rtl:pl-8 ltr:pr-8"><div><div handle="entries">Lista de salarios por región y año</div></div></td>
            </tr>
            <tr class="sortable-row outline-none" tabindex="0">
                <td><a href="{{ cp_route('calculadora.vacaciones_anteriores') }}">Vacaciones Anteriores</a></td>
                <td class="rtl:text-left ltr:text-right rtl:pl-8 ltr:pr-8"><div><div handle="entries">Lista de días de vacaciones por ley vigente hasta {{ $ano_vigencia_entero - 1 }}</div></div></td>
            </tr>
            <tr class="sortable-row outline-none" tabindex="0">
                <td><a href="{{ cp_route('calculadora.vacaciones_actuales') }}">Vacaciones Vigentes</a></td>
                <td class="rtl:text-left ltr:text-right rtl:pl-8 ltr:pr-8"><div><div handle="entries">Lista de días de vacaciones por ley vigente desde {{ $ano_vigencia_entero }}</div></div></td>
            </tr>
            
        </tbody>
    </table>
</div>
@endsection
