<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class CitasRegistrada extends Model
{
    use HasFactory;

    protected $fillable = [
        'cita_folio',
        'id_estado',
        'id_ccls',
        'correo',
        'celular',
        'cita_fecha',
        'cita_hora',
        'nombre',
        'apellidos',
        'observaciones',
        'status',
        'id_conciliador',
        'status_conciliador',
        'observaciones_conciliador',
        'id_configuracion'
    ];
        
}
