<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Crear usuarios base primero
        User::factory()->create([
            'name' => 'Rodrigo Galle',
            'email' => 'rodrigogalle14@gmail.com',
            'password' => bcrypt('mimundopq'),
        ]);

        User::factory()->create([
            'name' => 'Anderson Fernández',
            'email' => 'andersonfernandez@gmail.com',
            'password' => bcrypt('00000000'),
        ]);

        User::factory()->create([
            'name' => 'María Santos',
            'email' => 'maria.santos@gmail.com',
            'password' => bcrypt('12345678'),
        ]);

        User::factory()->create([
            'name' => 'Carlos Mendoza',
            'email' => 'carlos.tech@gmail.com',
            'password' => bcrypt('12345678'),
        ]);

        User::factory()->create([
            'name' => 'Ana López',
            'email' => 'ana.marketing@gmail.com',
            'password' => bcrypt('12345678'),
        ]);

        User::factory()->create([
            'name' => 'Luis Ramírez',
            'email' => 'luis.design@gmail.com',
            'password' => bcrypt('12345678'),
        ]);

        User::factory()->create([
            'name' => 'Sofía Castro',
            'email' => 'sofia.ai@gmail.com',
            'password' => bcrypt('12345678'),
        ]);

        // Ejecutar los seeders del sistema de tareas
        $this->call([
            ParticipantesSeeder::class, // Primero creamos los participantes
            TasksSeeder::class,         // Luego las tareas
        ]);
    }
}