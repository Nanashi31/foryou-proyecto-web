<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Empleado;
use Illuminate\Support\Facades\Hash;

class EmpleadoApiTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        // Create a user for authentication
        $this->user = User::factory()->create();
    }

    /** @test */
    public function a_user_can_retrieve_all_empleados()
    {
        Empleado::factory()->count(3)->create();

        $response = $this->actingAs($this->user, 'sanctum')->getJson('/api/empleados');

        $response->assertStatus(200)
                 ->assertJsonCount(3);
    }

    /** @test */
    public function a_user_can_create_an_empleado()
    {
        $password = $this->faker->password(8);
        $empleadoData = [
            'nombre' => $this->faker->name,
            'telefono' => $this->faker->phoneNumber,
            'correo' => $this->faker->unique()->safeEmail,
            'password' => $password,
            'rol' => $this->faker->randomElement(['admin', 'herrero', 'ventas']),
        ];

        $response = $this->actingAs($this->user, 'sanctum')->postJson('/api/empleados', $empleadoData);

        $response->assertStatus(201)
                 ->assertJsonFragment([
                     'nombre' => $empleadoData['nombre'],
                     'telefono' => $empleadoData['telefono'],
                     'correo' => $empleadoData['correo'],
                     'rol' => $empleadoData['rol'],
                 ]);

        $this->assertDatabaseHas('empleados', [
            'nombre' => $empleadoData['nombre'],
            'telefono' => $empleadoData['telefono'],
            'correo' => $empleadoData['correo'],
            'rol' => $empleadoData['rol'],
        ]);
        $createdEmpleado = Empleado::where('correo', $empleadoData['correo'])->first();
        $this->assertTrue(Hash::check($password, $createdEmpleado->password_hash));
    }

    /** @test */
    public function a_user_can_retrieve_a_specific_empleado()
    {
        $empleado = Empleado::factory()->create();

        $response = $this->actingAs($this->user, 'sanctum')->getJson('/api/empleados/' . $empleado->id_empleado);

        $response->assertStatus(200)
                 ->assertJson([
                     'nombre' => $empleado->nombre,
                     'telefono' => $empleado->telefono,
                     'correo' => $empleado->correo,
                     'rol' => $empleado->rol,
                 ]);
    }

    /** @test */
    public function a_user_can_update_an_empleado()
    {
        $empleado = Empleado::factory()->create();
        $updatedPassword = $this->faker->password(8);
        $updatedData = [
            'nombre' => 'Updated Employee Name',
            'telefono' => $this->faker->phoneNumber,
            'password' => $updatedPassword,
            'rol' => 'supervisor',
        ];

        $response = $this->actingAs($this->user, 'sanctum')->putJson('/api/empleados/' . $empleado->id_empleado, $updatedData);

        $response->assertStatus(200)
                 ->assertJsonFragment([
                     'nombre' => $updatedData['nombre'],
                     'telefono' => $updatedData['telefono'],
                     'rol' => $updatedData['rol'],
                 ]);

        $this->assertDatabaseHas('empleados', ['id_empleado' => $empleado->id_empleado, 'nombre' => $updatedData['nombre']]);
        $updatedEmpleado = Empleado::find($empleado->id_empleado);
        $this->assertTrue(Hash::check($updatedPassword, $updatedEmpleado->password_hash));
    }

    /** @test */
    public function a_user_can_delete_an_empleado()
    {
        $empleado = Empleado::factory()->create();

        $response = $this->actingAs($this->user, 'sanctum')->deleteJson('/api/empleados/' . $empleado->id_empleado);

        $response->assertStatus(204);

        $this->assertDatabaseMissing('empleados', ['id_empleado' => $empleado->id_empleado]);
    }

    /** @test */
    public function unauthenticated_users_cannot_access_empleado_endpoints()
    {
        $empleado = Empleado::factory()->create();

        $this->getJson('/api/empleados')->assertStatus(401);
        $this->postJson('/api/empleados', [])->assertStatus(401);
        $this->getJson('/api/empleados/' . $empleado->id_empleado)->assertStatus(401);
        $this->putJson('/api/empleados/' . $empleado->id_empleado, [])->assertStatus(401);
        $this->deleteJson('/api/empleados/' . $empleado->id_empleado)->assertStatus(401);
    }
}
