<?php

namespace Database\Factories;

use App\Models\Proyecto;
use App\Models\Cliente;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProyectoFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Proyecto::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'observaciones' => $this->faker->sentence,
            'plano_url' => $this->faker->url,
            'plano_json' => json_encode(['data' => $this->faker->words(3, true)]),
            'id_cliente' => Cliente::factory(),
        ];
    }
}
