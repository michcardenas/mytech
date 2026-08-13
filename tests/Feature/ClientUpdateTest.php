<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ClientUpdateTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        Role::firstOrCreate(['name' => 'admin']);
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        return $admin;
    }

    public function test_admin_actualiza_telefono_y_email_del_cliente(): void
    {
        $client = Client::create(['nombre' => 'Cliente X', 'telefono' => '111', 'email' => 'viejo@correo.com']);

        $response = $this->actingAs($this->admin())->patchJson(route('admin.clients.update', $client), [
            'telefono' => '+573001234567',
            'email' => 'nuevo@correo.com',
        ]);

        $response->assertOk()->assertJson(['ok' => true]);
        $this->assertDatabaseHas('clients', [
            'id' => $client->id,
            'telefono' => '+573001234567',
            'email' => 'nuevo@correo.com',
        ]);
    }

    public function test_email_invalido_es_rechazado(): void
    {
        $client = Client::create(['nombre' => 'Cliente Y', 'telefono' => '222']);

        $response = $this->actingAs($this->admin())->patchJson(route('admin.clients.update', $client), [
            'email' => 'no-es-un-email',
        ]);

        $response->assertStatus(422);
    }
}
