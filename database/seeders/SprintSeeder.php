<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Sprint;

class SprintSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Sprint::create([
            'nombre'       => 'Sprint 1',
            'fecha_inicio' => '2025-10-01',
            'fecha_fin'    => '2025-10-15',
            'estado'       => 'incompleto',
            'proyecto_id'  => 1, // Asegúrate de que exista el proyecto con id=1
        ]);

        Sprint::create([
            'nombre'       => 'Sprint 2',
            'fecha_inicio' => '2025-10-16',
            'fecha_fin'    => '2025-10-31',
            'estado'       => 'completo',
            'proyecto_id'  => 1,
        ]);
    }
}
