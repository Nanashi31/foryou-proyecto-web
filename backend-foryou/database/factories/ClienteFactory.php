<?php

namespace Database\Factories;

use App\Models\Cliente;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ClienteFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Cliente::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'id_cliente' => Str::uuid()->toString(),
            'nombre' => $this->faker->name,
            'usuario' => $this->faker->unique()->userName,
            'password_hash' => Hash::make('password'),
            'telefono' => $this->faker->phoneNumber,
            'domicilio' => $this->faker->address,
            'correo' => $this->faker->unique()->safeEmail,
            'auth_user_id' => null,
        ];
    }
}
