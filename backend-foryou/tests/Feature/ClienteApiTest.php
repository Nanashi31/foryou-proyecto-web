<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Cliente;
use Illuminate\Support\Facades\Hash;

class ClienteApiTest extends TestCase
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
    public function a_user_can_retrieve_all_clientes()
    {
        Cliente::factory()->count(3)->create();

        $response = $this->actingAs($this->user, 'sanctum')->getJson('/api/clientes');

        $response->assertStatus(200)
                 ->assertJsonCount(3);
    }

    /** @test */
    public function a_user_can_create_a_cliente()
    {
        $password = $this->faker->password(8);
        $clienteData = [
            'nombre' => $this->faker->name,
            'usuario' => $this->faker->unique()->userName,
            'password' => $password,
            'telefono' => $this->faker->phoneNumber,
            'domicilio' => $this->faker->address,
            'correo' => $this->faker->unique()->safeEmail,
        ];

        $response = $this->actingAs($this->user, 'sanctum')->postJson('/api/clientes', $clienteData);

        $response->assertStatus(201)
                 ->assertJsonFragment([
                     'nombre' => $clienteData['nombre'],
                     'usuario' => $clienteData['usuario'],
                     'telefono' => $clienteData['telefono'],
                     'domicilio' => $clienteData['domicilio'],
                     'correo' => $clienteData['correo'],
                 ]);

        $this->assertDatabaseHas('clientes', [
            'nombre' => $clienteData['nombre'],
            'usuario' => $clienteData['usuario'],
            'telefono' => $clienteData['telefono'],
            'domicilio' => $clienteData['domicilio'],
            'correo' => $clienteData['correo'],
        ]);
        $createdCliente = Cliente::where('correo', $clienteData['correo'])->first();
        $this->assertTrue(Hash::check($password, $createdCliente->password_hash));
    }

    /** @test */
    public function a_user_can_retrieve_a_specific_cliente()
    {
        $cliente = Cliente::factory()->create();

        $response = $this->actingAs($this->user, 'sanctum')->getJson('/api/clientes/' . $cliente->id_cliente);

        $response->assertStatus(200)
                 ->assertJson([
                     'nombre' => $cliente->nombre,
                     'usuario' => $cliente->usuario,
                     'telefono' => $cliente->telefono,
                     'domicilio' => $cliente->domicilio,
                     'correo' => $cliente->correo,
                 ]);
    }

    /** @test */
    public function a_user_can_update_a_cliente()
    {
        $cliente = Cliente::factory()->create();
        $updatedPassword = $this->faker->password(8);
        $updatedData = [
            'nombre' => 'Updated Client Name',
            'telefono' => $this->faker->phoneNumber,
            'password' => $updatedPassword,
        ];

        $response = $this->actingAs($this->user, 'sanctum')->putJson('/api/clientes/' . $cliente->id_cliente, $updatedData);

        $response->assertStatus(200)
                 ->assertJsonFragment([
                     'nombre' => $updatedData['nombre'],
                     'telefono' => $updatedData['telefono'],
                 ]);

        $this->assertDatabaseHas('clientes', ['id_cliente' => $cliente->id_cliente, 'nombre' => $updatedData['nombre']]);
        $updatedCliente = Cliente::find($cliente->id_cliente);
        $this->assertTrue(Hash::check($updatedPassword, $updatedCliente->password_hash));
    }

    /** @test */
    public function a_user_can_delete_a_cliente()
    {
        $cliente = Cliente::factory()->create();

        $response = $this->actingAs($this->user, 'sanctum')->deleteJson('/api/clientes/' . $cliente->id_cliente);

        $response->assertStatus(204);

        $this->assertDatabaseMissing('clientes', ['id_cliente' => $cliente->id_cliente]);
    }

    /** @test */
    public function unauthenticated_users_cannot_access_cliente_endpoints()
    {
        $cliente = Cliente::factory()->create();

        $this->getJson('/api/clientes')->assertStatus(401);
        $this->postJson('/api/clientes', [])->assertStatus(401);
        $this->getJson('/api/clientes/' . $cliente->id_cliente)->assertStatus(401);
        $this->putJson('/api/clientes/' . $cliente->id_cliente, [])->assertStatus(401);
        $this->deleteJson('/api/clientes/' . $cliente->id_cliente)->assertStatus(401);
    }
}
