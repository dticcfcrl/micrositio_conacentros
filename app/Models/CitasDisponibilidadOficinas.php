<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CitasDisponibilidadOficinas extends Model
{
    use HasFactory;

    protected $fillable = [  
        'id_administrador',
        'id_ccls',
        'horario',
        'status',
        'aplica',
        'created_at',
        'updated_at'
    ];
}