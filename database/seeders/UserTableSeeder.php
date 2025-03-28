<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        
        $users = [
            [
                'nombre' => 'super',
                'apellidos' => 'admin',
                'email' => 'admin@poa.com',
                'password' => bcrypt('password'),
                'no_personal' => '',
                'email_verificado' => now(),
                'id_estado' => 1,
                'id_ccls' => 1,
                'perfil' => 'superusuario',
                'status' => '1'
            ]
        ];

        foreach ($users as $key => $value) {
            $user = User::create($value);
            $user->assignRole($value['perfil']);
        }
    }
}
