<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Material;

class MaterialApiTest extends TestCase
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
    public function a_user_can_retrieve_all_materials()
    {
        Material::factory()->count(3)->create();

        $response = $this->actingAs($this->user, 'sanctum')->getJson('/api/materiales');

        $response->assertStatus(200)
                 ->assertJsonCount(3);
    }

    /** @test */
    public function a_user_can_create_a_material()
    {
        $materialData = [
            'nombre' => $this->faker->word,
            'stock' => $this->faker->randomFloat(2, 0, 1000),
            'costo_unitario' => $this->faker->randomFloat(2, 1, 100),
        ];

        $response = $this->actingAs($this->user, 'sanctum')->postJson('/api/materiales', $materialData);

        $response->assertStatus(201)
                 ->assertJsonFragment($materialData);

        $this->assertDatabaseHas('materiales', $materialData);
    }

    /** @test */
    public function a_user_can_retrieve_a_specific_material()
    {
        $material = Material::factory()->create();

        $response = $this->actingAs($this->user, 'sanctum')->getJson('/api/materiales/' . $material->id_material);

        $response->assertStatus(200)
                 ->assertJson([
                     'nombre' => $material->nombre,
                     'stock' => (float)$material->stock,
                     'costo_unitario' => (float)$material->costo_unitario,
                 ]);
    }

    /** @test */
    public function a_user_can_update_a_material()
    {
        $material = Material::factory()->create();
        $updatedData = [
            'nombre' => 'Updated Name',
            'stock' => 99.99,
            'costo_unitario' => 12.34,
        ];

        $response = $this->actingAs($this->user, 'sanctum')->putJson('/api/materiales/' . $material->id_material, $updatedData);

        $response->assertStatus(200)
                 ->assertJsonFragment($updatedData);

        $this->assertDatabaseHas('materiales', array_merge(['id_material' => $material->id_material], $updatedData));
    }

    /** @test */
    public function a_user_can_delete_a_material()
    {
        $material = Material::factory()->create();

        $response = $this->actingAs($this->user, 'sanctum')->deleteJson('/api/materiales/' . $material->id_material);

        $response->assertStatus(204);

        $this->assertDatabaseMissing('materiales', ['id_material' => $material->id_material]);
    }

    /** @test */
    public function unauthenticated_users_cannot_access_material_endpoints()
    {
        $material = Material::factory()->create();

        $this->getJson('/api/materiales')->assertStatus(401);
        $this->postJson('/api/materiales', [])->assertStatus(401);
        $this->getJson('/api/materiales/' . $material->id_material)->assertStatus(401);
        $this->putJson('/api/materiales/' . $material->id_material, [])->assertStatus(401);
        $this->deleteJson('/api/materiales/' . $material->id_material)->assertStatus(401);
    }
}
