<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {

        $roles = [
            [
                'name' => 'superusuario',
                'title' => 'Superusuario',
                'status' => true,
                'permissions' => ['dashboard', 'resumen_disponibilidad', 'users', 'editar_usuario', 'eliminar_usuario', 'agregar_usuario', 'listar_oficinas', 'agregar_oficina', 'eliminar_oficina', 'editar_oficina', 'mostrar_acciones_citas']
            ],
            [
                'name' => 'administrador',
                'title' => 'Administrador',
                'status' => true,
                'permissions' => ['dashboard', 'resumen_disponibilidad', 'cifras_control', 'configuracion', 'users', 'editar_usuario', 'eliminar_usuario', 'agregar_usuario', 'listar_oficinas', 'disponibilidad_oficina', 'disponibilidad_fechas_oficina', 'configurar_parametros', 'editar_oficina', 'atencion_citas', 'confirmar_cita', 'cancelar_cita', 'mostrar_acciones_citas']
            ],
            [
                'name' => 'conciliador',
                'title' => 'Conciliador',
                'status' => true,
                'permissions' => ['dashboard', 'resumen_disponibilidad', 'cifras_control', 'atencion_citas', 'confirmar_cita', 'cancelar_cita', 'mostrar_acciones_citas']
            ],
            [
                'name' => 'auxiliar',
                'title' => 'Auxiliar',
                'status' => true,
                'permissions' => ['dashboard', 'atencion_citas']
            ]
        ];

        foreach ($roles as $key => $value) {
            $permission = $value['permissions'];
            unset($value['permissions']);
            $role = Role::create($value);
            $role->givePermissionTo($permission);
        }
    }
}
