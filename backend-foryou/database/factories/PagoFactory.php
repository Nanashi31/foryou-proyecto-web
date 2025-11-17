<?php

namespace Database\Factories;

use App\Models\Pago;
use App\Models\Cotizacion;
use Illuminate\Database\Eloquent\Factories\Factory;

class PagoFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Pago::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'id_cot' => Cotizacion::factory(),
            'metodo' => $this->faker->randomElement(['Efectivo', 'Tarjeta', 'Transferencia']),
            'fec_pago' => $this->faker->dateTimeThisYear(),
            'cantidad' => $this->faker->randomFloat(2, 100, 5000),
        ];
    }
}
