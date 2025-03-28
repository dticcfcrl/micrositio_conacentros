<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {

        $permissions = [
            [
                'name' => 'dashboard',
                'title' => 'Resumen',
            ],
            [
                'name' => 'resumen_disponibilidad',
                'title' => 'Resumen disponibilidad',
            ],
            [
                'name' => 'cifras_control',
                'title' => 'Cifras control',
            ],
            [
                'name' => 'configuracion',
                'title' => 'Listar configuracion',
            ],
            [
                'name' => 'users',
                'title' => 'Listar usuarios',
            ],
            [
                'name' => 'agregar_usuario',
                'title' => 'Agregar usuario',                
            ],
            [
                'name' => 'editar_usuario',
                'title' => 'Editar usuario',                
            ],
            [
                'name' => 'eliminar_usuario',
                'title' => 'Eliminar usuario',
            ],
            [
                'name' => 'listar_oficinas',
                'title' => 'Listar oficinas',
            ],
            [
                'name' => 'disponibilidad_oficina',
                'title' => 'Listar disponibilidad',                
            ],
            [
                'name' => 'disponibilidad_fechas_oficina',
                'title' => 'Mostrar disponibilidad fechas',
            ],
            [
                'name' => 'configurar_parametros',
                'title' => 'Configurar parametros',
            ],            
            [
                'name' => 'agregar_oficina',
                'title' => 'Agregar oficinas',
            ],
            [
                'name' => 'eliminar_oficina',
                'title' => 'Eliminar oficinas',
            ],
            [
                'name' => 'editar_oficina',
                'title' => 'Editar oficina',
            ],            
            [
                'name' => 'atencion_citas',
                'title' => 'Listar citas',                
            ],
            [
                'name' => 'confirmar_cita',
                'title' => 'Confirmar cita',                
            ],
            [
                'name' => 'cancelar_cita',
                'title' => 'Cancelar cita',             
            ],
            [
                'name' => 'mostrar_acciones_citas',
                'title' => 'Mostrar acciones ciitas',             
            ],
        ];

        foreach ($permissions as $value) {
            Permission::create($value);
        }
    }
}
