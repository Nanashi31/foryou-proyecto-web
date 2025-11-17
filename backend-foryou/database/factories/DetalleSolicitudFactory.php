<?php

namespace Database\Factories;

use App\Models\DetalleSolicitud;
use App\Models\Solicitud;
use Illuminate\Database\Eloquent\Factories\Factory;

class DetalleSolicitudFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = DetalleSolicitud::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'id_solicitud' => Solicitud::factory(),
            'med_alt' => $this->faker->randomFloat(2, 1, 10),
            'med_anc' => $this->faker->randomFloat(2, 1, 10),
            'descripcion' => $this->faker->sentence,
        ];
    }
}
