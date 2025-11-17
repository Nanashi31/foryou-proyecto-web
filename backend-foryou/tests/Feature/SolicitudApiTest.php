<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Cliente;
use App\Models\Solicitud;

class SolicitudApiTest extends TestCase
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
    public function a_user_can_retrieve_all_solicitudes()
    {
        Solicitud::factory()->count(3)->create();

        $response = $this->actingAs($this->user, 'sanctum')->getJson('/api/solicitudes');

        $response->assertStatus(200)
                 ->assertJsonCount(3);
    }

    /** @test */
    public function a_user_can_create_a_solicitud()
    {
        $cliente = Cliente::factory()->create();
        $solicitudData = [
            'direccion' => $this->faker->address,
            'descripcion' => $this->faker->sentence,
            'id_cliente' => $cliente->id_cliente,
            'dias_disponibles' => 'Lunes, Miercoles, Viernes',
            'fecha_cita' => $this->faker->dateTimeBetween('now', '+1 week')->format('Y-m-d H:i:s'),
            'materiales' => 'Acero, Vidrio',
            'tipo_proyecto' => 'Puerta de forja',
        ];

        $response = $this->actingAs($this->user, 'sanctum')->postJson('/api/solicitudes', $solicitudData);

        $response->assertStatus(201)
                 ->assertJsonFragment([
                     'direccion' => $solicitudData['direccion'],
                     'descripcion' => $solicitudData['descripcion'],
                     'id_cliente' => $solicitudData['id_cliente'],
                     'dias_disponibles' => $solicitudData['dias_disponibles'],
                     'tipo_proyecto' => $solicitudData['tipo_proyecto'],
                 ]);

        $this->assertDatabaseHas('solicitudes', [
            'direccion' => $solicitudData['direccion'],
            'id_cliente' => $solicitudData['id_cliente'],
        ]);
    }

    /** @test */
    public function a_user_can_retrieve_a_specific_solicitud()
    {
        $solicitud = Solicitud::factory()->create();

        $response = $this->actingAs($this->user, 'sanctum')->getJson('/api/solicitudes/' . $solicitud->id_solicitud);

        $response->assertStatus(200)
                 ->assertJson([
                     'direccion' => $solicitud->direccion,
                     'descripcion' => $solicitud->descripcion,
                     'id_cliente' => $solicitud->id_cliente,
                 ]);
    }

    /** @test */
    public function a_user_can_update_a_solicitud()
    {
        $solicitud = Solicitud::factory()->create();
        $updatedData = [
            'direccion' => 'Updated Address',
            'descripcion' => 'Updated description for the project.',
            'tipo_proyecto' => 'Ventana de aluminio',
        ];

        $response = $this->actingAs($this->user, 'sanctum')->putJson('/api/solicitudes/' . $solicitud->id_solicitud, $updatedData);

        $response->assertStatus(200)
                 ->assertJsonFragment([
                     'direccion' => $updatedData['direccion'],
                     'descripcion' => $updatedData['descripcion'],
                     'tipo_proyecto' => $updatedData['tipo_proyecto'],
                 ]);

        $this->assertDatabaseHas('solicitudes', array_merge(['id_solicitud' => $solicitud->id_solicitud], $updatedData));
    }

    /** @test */
    public function a_user_can_delete_a_solicitud()
    {
        $solicitud = Solicitud::factory()->create();

        $response = $this->actingAs($this->user, 'sanctum')->deleteJson('/api/solicitudes/' . $solicitud->id_solicitud);

        $response->assertStatus(204);

        $this->assertDatabaseMissing('solicitudes', ['id_solicitud' => $solicitud->id_solicitud]);
    }

    /** @test */
    public function unauthenticated_users_cannot_access_solicitud_endpoints()
    {
        $solicitud = Solicitud::factory()->create();

        $this->getJson('/api/solicitudes')->assertStatus(401);
        $this->postJson('/api/solicitudes', [])->assertStatus(401);
        $this->getJson('/api/solicitudes/' . $solicitud->id_solicitud)->assertStatus(401);
        $this->putJson('/api/solicitudes/' . $solicitud->id_solicitud, [])->assertStatus(401);
        $this->deleteJson('/api/solicitudes/' . $solicitud->id_solicitud)->assertStatus(401);
    }
}
