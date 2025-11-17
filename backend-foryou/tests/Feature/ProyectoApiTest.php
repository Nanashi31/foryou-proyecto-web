<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Cliente;
use App\Models\Proyecto;

class ProyectoApiTest extends TestCase
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
    public function a_user_can_retrieve_all_proyectos()
    {
        Proyecto::factory()->count(3)->create();

        $response = $this->actingAs($this->user, 'sanctum')->getJson('/api/proyectos');

        $response->assertStatus(200)
                 ->assertJsonCount(3);
    }

    /** @test */
    public function a_user_can_create_a_proyecto()
    {
        $cliente = Cliente::factory()->create();
        $proyectoData = [
            'observaciones' => $this->faker->sentence,
            'plano_url' => $this->faker->url,
            'plano_json' => ['data' => $this->faker->words(3, true)], // Raw array, will be json_encoded by controller
            'id_cliente' => $cliente->id_cliente,
        ];

        $response = $this->actingAs($this->user, 'sanctum')->postJson('/api/proyectos', $proyectoData);

        $response->assertStatus(201)
                 ->assertJsonFragment([
                     'observaciones' => $proyectoData['observaciones'],
                     'plano_url' => $proyectoData['plano_url'],
                     'id_cliente' => $proyectoData['id_cliente'],
                 ]);

        $this->assertDatabaseHas('proyectos', [
            'observaciones' => $proyectoData['observaciones'],
            'id_cliente' => $proyectoData['id_cliente'],
        ]);
        $createdProyecto = Proyecto::where('id_cliente', $proyectoData['id_cliente'])->first();
        $this->assertEquals(json_encode($proyectoData['plano_json']), $createdProyecto->plano_json);
    }

    /** @test */
    public function a_user_can_retrieve_a_specific_proyecto()
    {
        $proyecto = Proyecto::factory()->create();

        $response = $this->actingAs($this->user, 'sanctum')->getJson('/api/proyectos/' . $proyecto->id_proyecto);

        $response->assertStatus(200)
                 ->assertJson([
                     'observaciones' => $proyecto->observaciones,
                     'plano_url' => $proyecto->plano_url,
                     'id_cliente' => $proyecto->id_cliente,
                 ]);
    }

    /** @test */
    public function a_user_can_update_a_proyecto()
    {
        $proyecto = Proyecto::factory()->create();
        $updatedData = [
            'observaciones' => 'Updated project observations.',
            'plano_url' => $this->faker->url,
            'plano_json' => ['updated_data' => $this->faker->words(2, true)],
        ];

        $response = $this->actingAs($this->user, 'sanctum')->putJson('/api/proyectos/' . $proyecto->id_proyecto, $updatedData);

        $response->assertStatus(200)
                 ->assertJsonFragment([
                     'observaciones' => $updatedData['observaciones'],
                     'plano_url' => $updatedData['plano_url'],
                 ]);

        $this->assertDatabaseHas('proyectos', ['id_proyecto' => $proyecto->id_proyecto, 'observaciones' => $updatedData['observaciones']]);
        $updatedProyecto = Proyecto::find($proyecto->id_proyecto);
        $this->assertEquals(json_encode($updatedData['plano_json']), $updatedProyecto->plano_json);
    }

    /** @test */
    public function a_user_can_delete_a_proyecto()
    {
        $proyecto = Proyecto::factory()->create();

        $response = $this->actingAs($this->user, 'sanctum')->deleteJson('/api/proyectos/' . $proyecto->id_proyecto);

        $response->assertStatus(204);

        $this->assertDatabaseMissing('proyectos', ['id_proyecto' => $proyecto->id_proyecto]);
    }

    /** @test */
    public function unauthenticated_users_cannot_access_proyecto_endpoints()
    {
        $proyecto = Proyecto::factory()->create();

        $this->getJson('/api/proyectos')->assertStatus(401);
        $this->postJson('/api/proyectos', [])->assertStatus(401);
        $this->getJson('/api/proyectos/' . $proyecto->id_proyecto)->assertStatus(401);
        $this->putJson('/api/proyectos/' . $proyecto->id_proyecto, [])->assertStatus(401);
        $this->deleteJson('/api/proyectos/' . $proyecto->id_proyecto)->assertStatus(401);
    }
}
