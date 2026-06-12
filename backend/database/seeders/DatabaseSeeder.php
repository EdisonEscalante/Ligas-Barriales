<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Rol;
use App\Models\UsuarioRol;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Crea los datos iniciales del sistema
     */
    public function run(): void
    {
        // Ejecutamos el seeder de roles
        $this->call(RolesSeeder::class);

        // Creamos el super administrador del sistema
        $superAdmin = User::firstOrCreate(
            ['email' => 'admin@ligasbarriales.com'],
            [
                'nombre'   => 'Super Administrador',
                'email'    => 'admin@ligasbarriales.com',
                'password' => Hash::make('admin12345'),
            ]
        );

        // Asignamos el rol de super_admin
        $rolSuperAdmin = Rol::where('nombre', 'super_admin')->first();

        UsuarioRol::firstOrCreate([
            'user_id' => $superAdmin->id,
            'rol_id'  => $rolSuperAdmin->id,
        ]);
    }
}