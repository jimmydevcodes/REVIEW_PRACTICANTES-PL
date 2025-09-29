<?php

namespace Database\Factories;
use App\Models\Sprint;
use App\Models\Proyecto;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Sprint>
 */
class SprintFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Sprint::class;

    public function definition(): array
    {
        $fechaInicio = $this->faker->dateTimeBetween('-6 months', 'now');
        $fechaFin = (clone $fechaInicio)->modify('+15 days');

        return [
            'nombre' => 'Sprint ' . $this->faker->unique()->numberBetween(1, 100),
            'fecha_inicio' => $fechaInicio,
            'fecha_fin' => $fechaFin,
            'estado' => $this->faker->randomElement(['completo', 'incompleto']),
            'proyecto_id' => Proyecto::factory(), 
        ];
    }
}
