<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Empresa;
use App\Models\Proyecto;

class ProyectoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        $empresa = Empresa::first();

        // cre ael priyecto y asigna id
        Proyecto::create([
            'nombre' => 'Proyecto Control de Asistencia',
            'descripcion' => 'Desarrollo de una aplicación web para gestión interna.',
            'fecha_inicio' => '2025-01-15',
            'fecha_fin' => '2025-06-30',
            'estado' => 'activo',
            'empresa_id' => $empresa->id,
        ]);
    }
}
