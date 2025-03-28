@extends('statamic::layout')
@section('title', 'Pregunta')

<!-- Include EasyMDE CSS -->
<link rel="stylesheet" href="https://unpkg.com/easymde/dist/easymde.min.css">

<!-- Include EasyMDE JS -->
<script src="https://unpkg.com/easymde/dist/easymde.min.js"></script>

<!-- Include Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<!-- Include jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Include Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

@section('content')
<div class="flex items-center mb-6">
    <h1 class="flex-1">Editar Pregunta</h1>
    <a href="{{ cp_route('chatbot.preguntas') }}" class="btn">Regresar</a>
</div>

<hr><br>
<div class="card p-3">
    <form action="{{ cp_route('chatbot.preguntas.update', $pregunta->id) }}" method="POST" class="max-w-lg mx-auto bg-white p-8 rounded-lg shadow-md">
        @csrf
        @method('PUT')
        <div class="mb-4">
            <label for="pregunta" class="block text-sm font-medium text-gray-700">Pregunta</label>
            <textarea name="pregunta" id="pregunta" rows="2" class="p-2 mt-1 block w-full rounded-md border border-grey-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>{{ $pregunta->pregunta }}</textarea>
        </div>

        <div class="mb-4">
            <label for="respuesta" class="block text-sm font-medium text-gray-700">Respuesta</label>
            <textarea name="respuesta" id="respuesta" required>{{ $respuesta }}</textarea>
        </div>

        <div class="mb-4">
            <label for="topico_id" class="block text-sm font-medium text-gray-700">Tópicos</label>
            <select name="topico_id[]" id="topico_id" multiple class="p-2 mt-1 block w-full rounded-md border border-grey-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                @foreach($topicos as $topico)
                    <option value="{{ $topico->id }}" {{ in_array($topico->id, $pregunta_topicos) ? 'selected' : '' }}>{{ $topico->nombre }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label for="subtopico_id" class="block text-sm font-medium text-gray-700">Subtópicos</label>
            <select name="subtopico_id[]" id="subtopico_id" multiple class="p-2 mt-1 block w-full rounded-md border border-grey-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                @foreach($subtopicos as $subtopico)
                    <option value="{{ $subtopico->id }}" {{ in_array($subtopico->id, $pregunta_subtopicos) ? 'selected' : '' }}>{{ $subtopico->nombre }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <button type="button" class="w-full text-left bg-gray-200 px-4 py-2 font-medium text-gray-700 rounded-md focus:outline-none flex justify-between items-center" onclick="toggleAccordion('palabras-clave')">
                Palabras Clave
                <svg id="icon-palabras-clave" class="w-3 h-3 transform rotate-0 transition-transform" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1l4 4 4-4"/>
                </svg>
            </button>
            <div id="palabras-clave" class="mt-0 p-3 hidden bg-gray-100 rounded-md">
                <div class="help-block mt-2">
                    <p>Tienen que ser en minúsculas y sín acentos.</p>
                </div>
                <hr>
                <div id="questions-container-palabras-clave">
                    @if($palabras_clave)
                        @foreach($palabras_clave as $index => $palabra_clave)
                            <div class="flex items-center mt-1">
                                <input type="text" name="palabras_clave[]" value="{{ $palabra_clave }}" class="p-2 block w-full rounded-md border border-grey-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="Nuevo elemento">
                                <button type="button" onclick="removeQuestion(event)" class="remove-question bg-red-500 text-white px-2 py-1 rounded flex items-center ml-2">
                                    <img src="{{ asset('svg/rubbish-bin.svg') }}" alt="Eliminar" class="w-5 h-5">
                                </button>
                            </div>
                        @endforeach
                    @endif
                </div>
                <button type="button" onclick="addQuestion('palabras-clave')" class="bg-blue-500 text-white px-4 py-2 rounded mt-2">+ Agregar Palabra Clave</button>
            </div>
        </div>

        <div class="mb-4">
            <button type="button" class="w-full text-left bg-gray-200 px-4 py-2 font-medium text-gray-700 rounded-md focus:outline-none flex justify-between items-center" onclick="toggleAccordion('preguntas-alternas')">
                Preguntas Alternas
                <svg id="icon-preguntas-alternas" class="w-3 h-3 transform rotate-0 transition-transform" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1l4 4 4-4"/>
                </svg>
            </button>
            <div id="preguntas-alternas" class="mt-0 p-3 hidden bg-gray-100 rounded-md">
                <hr>
                <div id="questions-container-preguntas-alternas">
                @if($preguntas_alternas)
                    @foreach($preguntas_alternas as $index => $pregunta_alterna)
                        <div class="flex items-center mt-1">
                            <input type="text" name="preguntas_alternas[]" value="{{ $pregunta_alterna }}" class="p-2 block w-full rounded-md border border-grey-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="Nuevo elemento">
                            <button type="button" onclick="removeQuestion(event)" class="remove-question bg-red-500 text-white px-2 py-1 rounded flex items-center ml-2">
                                <img src="{{ asset('svg/rubbish-bin.svg') }}" alt="Eliminar" class="w-5 h-5">
                            </button>
                        </div>
                    @endforeach
                @endif
            </div>
            <button type="button" onclick="addQuestion('preguntas-alternas')" class="bg-blue-500 text-white px-4 py-2 rounded mt-2">+ Agregar Pregunta Alterna</button>
            </div>
        </div>

        <div class="mb-4">
            <button type="button" class="w-full text-left bg-gray-200 px-4 py-2 font-medium text-gray-700 rounded-md focus:outline-none flex justify-between items-center" onclick="toggleAccordion('preguntas-relacionadas')">
                Preguntas Relacionadas
                <svg id="icon-preguntas-relacionadas" class="w-3 h-3 transform rotate-0 transition-transform" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1l4 4 4-4"/>
                </svg>
            </button>
            <div id="preguntas-relacionadas" class="mt-0 p-3 hidden bg-gray-100 rounded-md">
                <hr>
                <div class="mb-4">
                    <select name="preguntas_relacionadas[]" id="preguntas_relacionadas" class="select2-multiple p-2 mt-1 block w-full rounded-md border border-grey-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" multiple="multiple">
                        @foreach($preguntas as $p)
                            <option value="{{ $p->id }}" {{ in_array($p->id, $pregunta_relacionadas) ? 'selected' : '' }}>{{ $p->pregunta }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Lista de Sugerencias de Preguntas Relacionadas -->
                <div class="mb-4" id="sugerencias-container" style="display: none;">
                    <label class="block text-sm font-medium text-gray-700">Sugerencias de Preguntas Relacionadas</label>
                    <ul id="sugerencias-lista" class="list-disc pl-5 mt-2 ml-5">
                        <!-- Sugerencias dinámicas aquí -->
                    </ul>
                </div>
            </div>

            
            
        </div>

        <div class="flex justify-between">
            <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:text-sm">Guardar</button>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var easyMDE = new EasyMDE({ element: document.getElementById('respuesta'),
            maxHeight: "150px"  });

        easyMDE.codemirror.on('blur', function() {
            document.getElementById('respuesta').value = easyMDE.value();
        });

        document.getElementById('form_pregunta').addEventListener('submit', function () {
            document.getElementById('respuesta').value = easyMDE.value();
        });
    });

    function removeQuestion(event) {
        event.preventDefault();
        var button = event.target.closest('.remove-question');
        button.parentElement.remove();
    }

    function toggleAccordion(id) {
        var element = document.getElementById(id);
        var icon = document.getElementById('icon-' + id);
        element.classList.toggle('hidden');
        icon.classList.toggle('rotate-180');
    }

    function addQuestion(type) {
        const newQuestionDiv = document.createElement('div');
        newQuestionDiv.classList.add('flex', 'items-center', 'mt-1');
        let id = type.replace("-","_")
        newQuestionDiv.innerHTML = `
            <input type="text" name="${id}[]" class="p-2 block w-full rounded-md border border-grey-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="Nuevo elemento">
            <button type="button" onclick="removeQuestion(event)" class="remove-question bg-red-500 text-white px-2 py-1 rounded flex items-center ml-2">
                <img src="{{ asset('svg/rubbish-bin.svg') }}" alt="Eliminar" class="w-5 h-5">
            </button>
        `;
        document.getElementById('questions-container-' + type).appendChild(newQuestionDiv);
    }

    $(document).ready(function() {
        $('.select2-multiple').select2({
            placeholder: "Escriba las preguntas relacionadas",
            allowClear: true,
            width: '100%'
        });

        cargarSugerencias();

        $('#topico_id, #subtopico_id').on('change', function() {
            cargarSugerencias();
        });
    });

    function cargarSugerencias() {
        var topicos = $('#topico_id').val();
        var subtopicos = $('#subtopico_id').val();

        if (topicos.length > 0 && subtopicos.length > 0) {
            $.ajax({
                url: '{{ cp_route("chatbot.preguntas.sugerencias") }}',
                method: 'GET',
                data: {
                    topicos: topicos,
                    subtopicos: subtopicos
                },
                success: function(response) {
                    if (response.sugerencias.length > 0) {
                        $('#sugerencias-lista').empty();
                        response.sugerencias.forEach(function(sugerencia) {
                            $('#sugerencias-lista').append('<li class="text-gray-600">' + sugerencia.pregunta + '</li>');
                        });
                        $('#sugerencias-container').show();
                    } else {
                        $('#sugerencias-container').hide();
                    }
                }
            });
        } else {
            $('#sugerencias-container').hide();
        }
    }
</script>
@endsection
