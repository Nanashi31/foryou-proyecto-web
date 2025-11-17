<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Empleado;
use App\Models\Solicitud;
use App\Models\Visita;

class VisitaApiTest extends TestCase
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
    public function a_user_can_retrieve_all_visitas()
    {
        Visita::factory()->count(3)->create();

        $response = $this->actingAs($this->user, 'sanctum')->getJson('/api/visitas');

        $response->assertStatus(200)
                 ->assertJsonCount(3);
    }

    /** @test */
    public function a_user_can_create_a_visita()
    {
        $empleado = Empleado::factory()->create();
        $solicitud = Solicitud::factory()->create();
        $visitaData = [
            'id_empleado' => $empleado->id_empleado,
            'id_solicitud' => $solicitud->id_solicitud,
            'observaciones' => $this->faker->sentence,
        ];

        $response = $this->actingAs($this->user, 'sanctum')->postJson('/api/visitas', $visitaData);

        $response->assertStatus(201)
                 ->assertJsonFragment([
                     'id_empleado' => $visitaData['id_empleado'],
                     'id_solicitud' => $visitaData['id_solicitud'],
                     'observaciones' => $visitaData['observaciones'],
                 ]);

        $this->assertDatabaseHas('visitas', [
            'id_empleado' => $visitaData['id_empleado'],
            'id_solicitud' => $visitaData['id_solicitud'],
        ]);
    }

    /** @test */
    public function a_user_can_retrieve_a_specific_visita()
    {
        $visita = Visita::factory()->create();

        $response = $this->actingAs($this->user, 'sanctum')->getJson('/api/visitas/' . $visita->id_visita);

        $response->assertStatus(200)
                 ->assertJson([
                     'id_empleado' => $visita->id_empleado,
                     'id_solicitud' => $visita->id_solicitud,
                     'observaciones' => $visita->observaciones,
                 ]);
    }

    /** @test */
    public function a_user_can_update_a_visita()
    {
        $visita = Visita::factory()->create();
        $newEmpleado = Empleado::factory()->create();
        $updatedData = [
            'id_empleado' => $newEmpleado->id_empleado,
            'observaciones' => 'Updated observations for the visit.',
        ];

        $response = $this->actingAs($this->user, 'sanctum')->putJson('/api/visitas/' . $visita->id_visita, $updatedData);

        $response->assertStatus(200)
                 ->assertJsonFragment([
                     'id_empleado' => $updatedData['id_empleado'],
                     'observaciones' => $updatedData['observaciones'],
                 ]);

        $this->assertDatabaseHas('visitas', array_merge(['id_visita' => $visita->id_visita], $updatedData));
    }

    /** @test */
    public function a_user_can_delete_a_visita()
    {
        $visita = Visita::factory()->create();

        $response = $this->actingAs($this->user, 'sanctum')->deleteJson('/api/visitas/' . $visita->id_visita);

        $response->assertStatus(204);

        $this->assertDatabaseMissing('visitas', ['id_visita' => $visita->id_visita]);
    }

    /** @test */
    public function unauthenticated_users_cannot_access_visita_endpoints()
    {
        $visita = Visita::factory()->create();

        $this->getJson('/api/visitas')->assertStatus(401);
        $this->postJson('/api/visitas', [])->assertStatus(401);
        $this->getJson('/api/visitas/' . $visita->id_visita)->assertStatus(401);
        $this->putJson('/api/visitas/' . $visita->id_visita, [])->assertStatus(401);
        $this->deleteJson('/api/visitas/' . $visita->id_visita)->assertStatus(401);
    }
}
