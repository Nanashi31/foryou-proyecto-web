<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Solicitud;
use App\Models\DetalleSolicitud;

class DetalleSolicitudApiTest extends TestCase
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
    public function a_user_can_retrieve_all_detalles_solicitud()
    {
        DetalleSolicitud::factory()->count(3)->create();

        $response = $this->actingAs($this->user, 'sanctum')->getJson('/api/detalles-solicitud');

        $response->assertStatus(200)
                 ->assertJsonCount(3);
    }

    /** @test */
    public function a_user_can_create_a_detalle_solicitud()
    {
        $solicitud = Solicitud::factory()->create();
        $detalleData = [
            'id_solicitud' => $solicitud->id_solicitud,
            'med_alt' => $this->faker->randomFloat(2, 1, 10),
            'med_anc' => $this->faker->randomFloat(2, 1, 10),
            'descripcion' => $this->faker->sentence,
        ];

        $response = $this->actingAs($this->user, 'sanctum')->postJson('/api/detalles-solicitud', $detalleData);

        $response->assertStatus(201)
                 ->assertJsonFragment([
                     'id_solicitud' => $detalleData['id_solicitud'],
                     'med_alt' => (float)$detalleData['med_alt'],
                     'med_anc' => (float)$detalleData['med_anc'],
                     'descripcion' => $detalleData['descripcion'],
                 ]);

        $this->assertDatabaseHas('detalles_solicitud', [
            'id_solicitud' => $detalleData['id_solicitud'],
            'descripcion' => $detalleData['descripcion'],
        ]);
    }

    /** @test */
    public function a_user_can_retrieve_a_specific_detalle_solicitud()
    {
        $detalle = DetalleSolicitud::factory()->create();

        $response = $this->actingAs($this->user, 'sanctum')->getJson('/api/detalles-solicitud/' . $detalle->id_detalles);

        $response->assertStatus(200)
                 ->assertJson([
                     'id_solicitud' => $detalle->id_solicitud,
                     'med_alt' => (float)$detalle->med_alt,
                     'med_anc' => (float)$detalle->med_anc,
                     'descripcion' => $detalle->descripcion,
                 ]);
    }

    /** @test */
    public function a_user_can_update_a_detalle_solicitud()
    {
        $detalle = DetalleSolicitud::factory()->create();
        $updatedData = [
            'med_alt' => 5.50,
            'descripcion' => 'Updated detail description.',
        ];

        $response = $this->actingAs($this->user, 'sanctum')->putJson('/api/detalles-solicitud/' . $detalle->id_detalles, $updatedData);

        $response->assertStatus(200)
                 ->assertJsonFragment([
                     'med_alt' => (float)$updatedData['med_alt'],
                     'descripcion' => $updatedData['descripcion'],
                 ]);

        $this->assertDatabaseHas('detalles_solicitud', array_merge(['id_detalles' => $detalle->id_detalles], $updatedData));
    }

    /** @test */
    public function a_user_can_delete_a_detalle_solicitud()
    {
        $detalle = DetalleSolicitud::factory()->create();

        $response = $this->actingAs($this->user, 'sanctum')->deleteJson('/api/detalles-solicitud/' . $detalle->id_detalles);

        $response->assertStatus(204);

        $this->assertDatabaseMissing('detalles_solicitud', ['id_detalles' => $detalle->id_detalles]);
    }

    /** @test */
    public function unauthenticated_users_cannot_access_detalles_solicitud_endpoints()
    {
        $detalle = DetalleSolicitud::factory()->create();

        $this->getJson('/api/detalles-solicitud')->assertStatus(401);
        $this->postJson('/api/detalles-solicitud', [])->assertStatus(401);
        $this->getJson('/api/detalles-solicitud/' . $detalle->id_detalles)->assertStatus(401);
        $this->putJson('/api/detalles-solicitud/' . $detalle->id_detalles, [])->assertStatus(401);
        $this->deleteJson('/api/detalles-solicitud/' . $detalle->id_detalles)->assertStatus(401);
    }
}
