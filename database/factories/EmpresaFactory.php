<?php

namespace Database\Factories;
use App\Models\Proyecto;
use App\Models\Empresa;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Empresa>
 */
class EmpresaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    protected $model = Empresa::class;

    public function definition(): array
    {
         return [
            'nombre' => $this->faker->company,
            'ruc' => $this->faker->unique()->numerify('###########'), // 11 dígitos
        ];
    }
}
