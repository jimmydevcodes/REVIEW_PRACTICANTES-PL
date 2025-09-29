<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Empresa;
class EmpresaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Empresa::create([
            'nombre' => 'Cuantica',
            'ruc'    => '20123456789', 
        ]);

        Empresa::create([
            'nombre' => 'Anderson',
            'ruc'    => '20987654321', 
        ]);
    }
}
