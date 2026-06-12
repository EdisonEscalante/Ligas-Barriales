<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Rol;

class RolesSeeder extends Seeder
{
    /**
     * Crea los roles base del sistema
     */
    public function run(): void
    {
        $roles = [
            [
                'nombre'      => 'super_admin',
                'descripcion' => 'Administrador general del sistema. Gestiona todas las ligas.',
            ],
            [
                'nombre'      => 'admin_liga',
                'descripcion' => 'Administrador de una liga específica.',
            ],
            [
                'nombre'      => 'admin_equipo',
                'descripcion' => 'Administrador de un equipo dentro de una liga.',
            ],
            [
                'nombre'      => 'calificador',
                'descripcion' => 'Registra resultados y estadísticas de partidos.',
            ],
            [
                'nombre'      => 'sancionador',
                'descripcion' => 'Gestiona amonestaciones y sanciones.',
            ],
            [
                'nombre'      => 'delegado',
                'descripcion' => 'Delegado de equipo, gestiona inscripciones de jugadores.',
            ],
        ];

        foreach ($roles as $rol) {
            Rol::firstOrCreate(['nombre' => $rol['nombre']], $rol);
        }
    }
}