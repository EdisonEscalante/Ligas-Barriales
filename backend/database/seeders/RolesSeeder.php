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
                'nombre'      => 'admin_equipos',
                'descripcion' => 'Registra equipos y valida los jugadores ingresados por los delegados.',
            ],
            [
                'nombre'      => 'calificador',
                'descripcion' => 'Registra resultados y estadísticas de partidos.',
            ],
            [
                'nombre'      => 'programador',
                'descripcion' => 'Gestiona el calendario de fechas y encuentros.',
            ],
            [
                'nombre'      => 'sancionador',
                'descripcion' => 'Gestiona amonestaciones y sanciones.',
            ],
            [
                'nombre'      => 'delegado',
                'descripcion' => 'Presidente de equipo. Registra los jugadores de su equipo.',
            ],
        ];

        foreach ($roles as $rol) {
            Rol::firstOrCreate(['nombre' => $rol['nombre']], $rol);
        }
    }
}