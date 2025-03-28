@extends('statamic::layout')
@section('title', 'Servidor')

@section('content')

<div class="flex items-center mb-6">
    <h1 class="flex-1">Servidor</h1>
    @if (!is_null($cambios) && count($cambios) > 0)
        <button class="btn-primary" data-toggle="modal" data-target="#confirmBackup" onclick="showModal()">Realizar respaldo</button>
    @endif
</div>
<p>Sección para realizar acciones con el servidor: Realizar el respaldo de los cambios y visualizar los cambios por guardar.</p>
<small>Nomenclatura:
    <ul>
        <li>- M: Modified (Modificado o actualizado)</li>
        <li>- UT: UnTracked (Archivo nuevo)</li>
        <li>- D: Deleted (Eliminado)</li>
    </ul>
</small>

<hr>
<div class="container mx-auto mt-5">
    <div class="bg-blue-200 border border-blue-400 text-blue-700 px-4 py-3 rounded relative" role="alert">
        <span class="block sm:inline">
            <strong class="font-bold">Nota:</strong>
            Se recomienda realizar los respaldos en horarios fuera del horario de actividad operativa para evitar posibles incidentes.</span>
    </div>
</div>
<br>


    @if(is_null($cambios))
    <div class="container mx-auto mt-5">
        <div class="bg-red-100 border border-red-200 text-red-500 px-4 py-3 rounded relative text-center" role="alert">
            <span class="block sm:inline">
                Por favor, contacta a administración. Los cambios pendientes no se están registrando en el servidor para realizar el respaldo.
            </span>
            <small>Código de error: servidor-respaldo.</small>
        </div>
    </div>
    @else
        @if(count($cambios) > 0)
            <div class="card p-0">
                <table class="w-full text-center bg-white">
                    <thead>
                        <tr>
                            <th class="py-2 px-4 bg-gray-200 font-bold uppercase text-sm text-gray-600">Estado</th>
                            <th class="py-2 px-4 bg-gray-200 font-bold uppercase text-sm text-gray-600">Archivo</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($cambios as $cambio)
                            <tr>
                                <td class="py-2 px-4 border-b border-gray-200 max-w-xs w-1/4">{{ substr($cambio, 0, 2) }}</td>
                                <td class="py-2 px-4 border-b border-gray-200 max-w-xs w-3/4">{{ substr($cambio, 3) }}</td>                
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Modal -->
            <div id="confirmBackup" class="hidden fixed z-10 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>

                <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                    <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg p-3">
                        <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                            <div class="sm:flex sm:items-start">
                                <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                                    <h3 class="text-base font-semibold leading-6 text-gray-900" id="modal-title">Confirmar Respaldo</h3>
                                    <div class="mt-2">
                                        <p class="text-sm text-gray-700">¿Estás seguro en subir los cambios? Este proceso puede tardar varios minutos.</p>
                                        <div class="container mx-auto mt-5 text-sm">
                                            <div class="bg-blue-200 border border-blue-400 text-blue-700 px-4 py-3 rounded relative" role="alert">
                                                <span class="block sm:inline">
                                                    <strong class="font-bold">Recuerda:</strong>
                                                    Realizar los respaldos en horarios fuera del horario de actividad operativa para evitar posibles incidentes.</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="">
                            <button type="button" class="btn inline-flex" onclick="hideModal()">Cancelar</button>
                            <form id="backupForm" action="" method="POST" class="inline">
                                @csrf
                                @method('POST')
                                <button type="submit" class="btn btn-primary inline-flex">Continuar</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <script>
                function showModal() {
                    document.getElementById('backupForm').action = "{{ cp_route('server.backup') }}";
                    document.getElementById('confirmBackup').classList.remove('hidden');
                }
            
                function hideModal() {
                    document.getElementById('confirmBackup').classList.add('hidden');
                }
            </script>
        @else
            <div class="border text-gray px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">
                    No hay cambios pendientes.
                </span>
            </div>
        @endif
    @endif
    
@endsection
