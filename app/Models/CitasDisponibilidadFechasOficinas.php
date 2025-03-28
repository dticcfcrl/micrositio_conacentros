<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CitasDisponibilidadFechasOficinas extends Model
{
    use HasFactory;

    protected $fillable = [        
        'id_administrador',
        'id_ccls',
        'fecha',
        'status',
        'created_at',
        'updated_at'
    ];
}