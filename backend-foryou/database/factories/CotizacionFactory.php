<?php

namespace Database\Factories;

use App\Models\Cotizacion;
use App\Models\Solicitud;
use Illuminate\Database\Eloquent\Factories\Factory;

class CotizacionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Cotizacion::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'id_solicitud' => Solicitud::factory(),
            'fecha_cot' => $this->faker->dateTimeThisYear(),
            'costo_total' => $this->faker->randomFloat(2, 100, 5000),
            'notas' => $this->faker->sentence,
        ];
    }
}
