<?php

namespace Database\Factories;

use App\Models\Visita;
use App\Models\Empleado;
use App\Models\Solicitud;
use Illuminate\Database\Eloquent\Factories\Factory;

class VisitaFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Visita::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'id_empleado' => Empleado::factory(),
            'id_solicitud' => Solicitud::factory(),
            'fecha' => $this->faker->dateTimeThisYear(),
            'observaciones' => $this->faker->sentence,
        ];
    }
}
