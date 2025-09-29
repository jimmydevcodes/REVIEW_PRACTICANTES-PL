<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Participante;
use Illuminate\Database\Seeder;

class ParticipantesSeeder extends Seeder
{
    public function run(): void
    {
        $participantesData = [
            'rodrigogalle14@gmail.com' => [
                'nombre' => 'Rodrigo',
                'apellido' => 'Galle',
                'teléfono' => '+51 999123456',
                'id_área' => 1,
                'id_cargo' => 3
            ],
            'andersonfernandez@gmail.com' => [
                'nombre' => 'Anderson',
                'apellido' => 'Fernández',
                'teléfono' => '+51 888234567',
                'id_área' => 1,
                'id_cargo' => 2
            ],
            'maria.santos@gmail.com' => [
                'nombre' => 'María',
                'apellido' => 'Santos',
                'teléfono' => '+51 777345678',
                'id_área' => 1,
                'id_cargo' => 1
            ],
            'carlos.tech@gmail.com' => [
                'nombre' => 'Carlos',
                'apellido' => 'Mendoza',
                'teléfono' => '+51 666456789',
                'id_área' => 1,
                'id_cargo' => 7
            ],
            'ana.marketing@gmail.com' => [
                'nombre' => 'Ana',
                'apellido' => 'López',
                'teléfono' => '+51 555567890',
                'id_área' => 2,
                'id_cargo' => 4
            ],
            'luis.design@gmail.com' => [
                'nombre' => 'Luis',
                'apellido' => 'Ramírez',
                'teléfono' => '+51 444678901',
                'id_área' => 3,
                'id_cargo' => 5
            ],
            'sofia.ai@gmail.com' => [
                'nombre' => 'Sofía',
                'apellido' => 'Castro',
                'teléfono' => '+51 333789012',
                'id_área' => 4,
                'id_cargo' => 6
            ]
        ];

        foreach ($participantesData as $email => $data) {
            $user = User::where('email', $email)->first();
            if ($user) {
                $data['id_participante'] = $user->id;
                $data['user_id'] = $user->id;
                $data['correo'] = $email;
                Participante::create($data);
            }
        }
    }
}