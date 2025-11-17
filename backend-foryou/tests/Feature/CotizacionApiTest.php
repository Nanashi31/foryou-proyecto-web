<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Solicitud;
use App\Models\Cotizacion;
use App\Models\Material;

class CotizacionApiTest extends TestCase
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
    public function a_user_can_retrieve_all_cotizaciones()
    {
        Cotizacion::factory()->count(3)->create();

        $response = $this->actingAs($this->user, 'sanctum')->getJson('/api/cotizaciones');

        $response->assertStatus(200)
                 ->assertJsonCount(3);
    }

    /** @test */
    public function a_user_can_create_a_cotizacion()
    {
        $solicitud = Solicitud::factory()->create();
        $cotizacionData = [
            'id_solicitud' => $solicitud->id_solicitud,
            'costo_total' => $this->faker->randomFloat(2, 100, 5000),
            'notas' => $this->faker->sentence,
        ];

        $response = $this->actingAs($this->user, 'sanctum')->postJson('/api/cotizaciones', $cotizacionData);

        $response->assertStatus(201)
                 ->assertJsonFragment([
                     'id_solicitud' => $cotizacionData['id_solicitud'],
                     'costo_total' => (float)$cotizacionData['costo_total'],
                     'notas' => $cotizacionData['notas'],
                 ]);

        $this->assertDatabaseHas('cotizaciones', [
            'id_solicitud' => $cotizacionData['id_solicitud'],
            'costo_total' => $cotizacionData['costo_total'],
        ]);
    }

    /** @test */
    public function a_user_can_retrieve_a_specific_cotizacion()
    {
        $cotizacion = Cotizacion::factory()->create();

        $response = $this->actingAs($this->user, 'sanctum')->getJson('/api/cotizaciones/' . $cotizacion->id_cotizacion);

        $response->assertStatus(200)
                 ->assertJson([
                     'id_solicitud' => $cotizacion->id_solicitud,
                     'costo_total' => (float)$cotizacion->costo_total,
                     'notas' => $cotizacion->notas,
                 ]);
    }

    /** @test */
    public function a_user_can_update_a_cotizacion()
    {
        $cotizacion = Cotizacion::factory()->create();
        $updatedData = [
            'costo_total' => 999.99,
            'notas' => 'Updated notes for the quote.',
        ];

        $response = $this->actingAs($this->user, 'sanctum')->putJson('/api/cotizaciones/' . $cotizacion->id_cotizacion, $updatedData);

        $response->assertStatus(200)
                 ->assertJsonFragment([
                     'costo_total' => (float)$updatedData['costo_total'],
                     'notas' => $updatedData['notas'],
                 ]);

        $this->assertDatabaseHas('cotizaciones', array_merge(['id_cotizacion' => $cotizacion->id_cotizacion], $updatedData));
    }

    /** @test */
    public function a_user_can_delete_a_cotizacion()
    {
        $cotizacion = Cotizacion::factory()->create();

        $response = $this->actingAs($this->user, 'sanctum')->deleteJson('/api/cotizaciones/' . $cotizacion->id_cotizacion);

        $response->assertStatus(204);

        $this->assertDatabaseMissing('cotizaciones', ['id_cotizacion' => $cotizacion->id_cotizacion]);
    }

    /** @test */
    public function a_user_can_get_ai_material_suggestions_for_a_solicitud()
    {
        $solicitud = Solicitud::factory()->create([
            'tipo_proyecto' => 'puerta',
            'descripcion' => 'Una puerta de metal para entrada principal.',
        ]);

        $response = $this->actingAs($this->user, 'sanctum')->postJson('/api/cotizaciones/suggest-materials', [
            'id_solicitud' => $solicitud->id_solicitud,
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'message',
                     'solicitud_id',
                     'suggestions' => [
                         '*' => ['material_name', 'quantity', 'unit', 'notes']
                     ]
                 ])
                 ->assertJsonFragment(['solicitud_id' => $solicitud->id_solicitud]);
    }

    /** @test */
    public function unauthenticated_users_cannot_access_cotizacion_endpoints()
    {
        $cotizacion = Cotizacion::factory()->create();
        $solicitud = Solicitud::factory()->create();

        $this->getJson('/api/cotizaciones')->assertStatus(401);
        $this->postJson('/api/cotizaciones', [])->assertStatus(401);
        $this->getJson('/api/cotizaciones/' . $cotizacion->id_cotizacion)->assertStatus(401);
        $this->putJson('/api/cotizaciones/' . $cotizacion->id_cotizacion, [])->assertStatus(401);
        $this->deleteJson('/api/cotizaciones/' . $cotizacion->id_cotizacion)->assertStatus(401);
        $this->postJson('/api/cotizaciones/suggest-materials', ['id_solicitud' => $solicitud->id_solicitud])->assertStatus(401);
    }
}
