<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CitasConfiguracionOficinas extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_administrador',
        'id_ccls',
        'total_conciliadores',
        'total_auxiliares',
        'status_lunes',
        'status_martes',
        'status_miercoles',
        'status_jueves',
        'status_viernes',
        'hora_cita_inicio',
        'hora_cita_fin',
        'minutos_cita',
        'meses_cita',
        'hora_comida_inicio',
        'hora_comida_fin',
        'status',
        'aplica',
        'created_at',
        'updated_at'
    ];
}
