<?php

namespace Meridiano\Catalogs\Http\Controllers;

use Statamic\Http\Controllers\CP\CpController;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Illuminate\Support\Facades\Log;

class ServerController extends CpController
{
    public function index()
    {
        $this->authorize('view server');
        $cambios = $this->get_pending_changes();
        
        return view('catalog::server.index', compact('cambios'));
    }

    public function backup()
    {
        $this->authorize('view server');
        $message = "Respaldo desde el sevidor. Fecha: " . date('Y-m-d H:i:s'). "";
        $scriptPath = base_path('scripts/commit.sh');
        $path = base_path('');

        $process = new Process([$scriptPath, $path, $message]);
        $process->setTimeout(300);
        $process->run();

        if (!$process->isSuccessful()) {
            Log::error($process->getErrorOutput());
            Log::error($process->getOutput());
            return redirect()->to(cp_route('server.index'))->withError('No se pudo procesar la información, intentelo nuevamente o contacte al administrador ');
        }

        return redirect()->to(cp_route('server.index'))->withSuccess('Se respaldaron los cambios pendientes exitosamente');
    }

    public function get_pending_changes() {
        $process = new Process(['git', 'status', '--porcelain']);
        $process->run();
        $changes = null;

        if ($process->isSuccessful()) {
            $output = $process->getOutput();
            $changes = array_filter(explode("\n", $output));

            $changes = array_map(function($change) {
                return str_replace('??', 'UT', $change);
            }, $changes);
        }

        return $changes;    
    }
}