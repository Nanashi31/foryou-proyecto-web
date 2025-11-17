<?php

namespace Database\Factories;

use App\Models\Solicitud;
use App\Models\Cliente;
use Illuminate\Database\Eloquent\Factories\Factory;

class SolicitudFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Solicitud::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'direccion' => $this->faker->address,
            'descripcion' => $this->faker->sentence,
            'fecha' => $this->faker->dateTimeThisYear(),
            'id_cliente' => Cliente::factory(),
            'dias_disponibles' => 'Lunes, Miercoles, Viernes',
            'fecha_cita' => $this->faker->dateTimeBetween('now', '+1 month'),
            'materiales' => $this->faker->words(3, true),
            'tipo_proyecto' => $this->faker->randomElement(['Puerta', 'Ventana', 'Barandal', 'Escalera']),
        ];
    }
}
