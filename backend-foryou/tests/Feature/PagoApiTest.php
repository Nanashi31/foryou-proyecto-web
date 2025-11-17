<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Cotizacion;
use App\Models\Pago;

class PagoApiTest extends TestCase
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
    public function a_user_can_retrieve_all_pagos()
    {
        Pago::factory()->count(3)->create();

        $response = $this->actingAs($this->user, 'sanctum')->getJson('/api/pagos');

        $response->assertStatus(200)
                 ->assertJsonCount(3);
    }

    /** @test */
    public function a_user_can_create_a_pago()
    {
        $cotizacion = Cotizacion::factory()->create();
        $pagoData = [
            'id_cot' => $cotizacion->id_cotizacion,
            'metodo' => $this->faker->randomElement(['Efectivo', 'Tarjeta', 'Transferencia']),
            'cantidad' => $this->faker->randomFloat(2, 100, 5000),
        ];

        $response = $this->actingAs($this->user, 'sanctum')->postJson('/api/pagos', $pagoData);

        $response->assertStatus(201)
                 ->assertJsonFragment([
                     'id_cot' => $pagoData['id_cot'],
                     'metodo' => $pagoData['metodo'],
                     'cantidad' => (float)$pagoData['cantidad'],
                 ]);

        $this->assertDatabaseHas('pagos', [
            'id_cot' => $pagoData['id_cot'],
            'metodo' => $pagoData['metodo'],
        ]);
    }

    /** @test */
    public function a_user_can_retrieve_a_specific_pago()
    {
        $pago = Pago::factory()->create();

        $response = $this->actingAs($this->user, 'sanctum')->getJson('/api/pagos/' . $pago->id_pago);

        $response->assertStatus(200)
                 ->assertJson([
                     'id_cot' => $pago->id_cot,
                     'metodo' => $pago->metodo,
                     'cantidad' => (float)$pago->cantidad,
                 ]);
    }

    /** @test */
    public function a_user_can_update_a_pago()
    {
        $pago = Pago::factory()->create();
        $updatedData = [
            'metodo' => 'Transferencia Bancaria',
            'cantidad' => 1234.56,
        ];

        $response = $this->actingAs($this->user, 'sanctum')->putJson('/api/pagos/' . $pago->id_pago, $updatedData);

        $response->assertStatus(200)
                 ->assertJsonFragment([
                     'metodo' => $updatedData['metodo'],
                     'cantidad' => (float)$updatedData['cantidad'],
                 ]);

        $this->assertDatabaseHas('pagos', array_merge(['id_pago' => $pago->id_pago], $updatedData));
    }

    /** @test */
    public function a_user_can_delete_a_pago()
    {
        $pago = Pago::factory()->create();

        $response = $this->actingAs($this->user, 'sanctum')->deleteJson('/api/pagos/' . $pago->id_pago);

        $response->assertStatus(204);

        $this->assertDatabaseMissing('pagos', ['id_pago' => $pago->id_pago]);
    }

    /** @test */
    public function unauthenticated_users_cannot_access_pago_endpoints()
    {
        $pago = Pago::factory()->create();

        $this->getJson('/api/pagos')->assertStatus(401);
        $this->postJson('/api/pagos', [])->assertStatus(401);
        $this->getJson('/api/pagos/' . $pago->id_pago)->assertStatus(401);
        $this->putJson('/api/pagos/' . $pago->id_pago, [])->assertStatus(401);
        $this->deleteJson('/api/pagos/' . $pago->id_pago)->assertStatus(401);
    }
}
