<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CitasConfigurarVacaciones extends Model
{
    use HasFactory;

    protected $fillable = [        
        'id_responsable',
        'id_usuario',
        'fecha',
        'status',
        'created_at',
        'updated_at'
    ];
}
