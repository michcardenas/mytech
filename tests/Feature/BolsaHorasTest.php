<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\InternalProject;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class BolsaHorasTest extends TestCase
{
    use RefreshDatabase;

    private function crearBolsa(): InternalProject
    {
        $client = Client::create([
            'nombre' => 'Cliente Bolsa',
            'telefono' => '+573001112233',
        ]);

        $project = InternalProject::create([
            'nombre' => 'Bolsa 40h',
            'cliente_nombre' => 'Cliente Bolsa',
            'cliente_contacto' => '+573001112233',
            'client_id' => $client->id,
            'precio' => 4000000,
            'moneda' => 'COP',
            'estado' => 'en_progreso',
            'es_bolsa_horas' => true,
            'horas_totales' => 40,
            'valor_hora' => 100000,
            'puntos_acuerdo' => [
                ['texto' => 'Modulo de reportes', 'horas' => 10, 'estado' => 'hecho'],
                ['texto' => 'Integracion pasarela', 'horas' => 8, 'estado' => 'en_progreso'],
            ],
        ]);

        $project->bolsaMovimientos()->create(['fecha' => '2026-08-01', 'descripcion' => 'Setup inicial', 'horas' => 5]);
        $project->bolsaMovimientos()->create(['fecha' => '2026-08-05', 'descripcion' => 'Ajustes checkout', 'horas' => 2.5]);

        return $project;
    }

    public function test_accessors_de_horas_calculan_consumo_y_restante(): void
    {
        $project = $this->crearBolsa()->fresh('bolsaMovimientos');

        $this->assertEqualsWithDelta(7.5, $project->horas_consumidas, 0.001);
        $this->assertEqualsWithDelta(32.5, $project->horas_restantes, 0.001);
        $this->assertSame(19, $project->porcentaje_horas); // 7.5 / 40 = 18.75 -> 19
    }

    public function test_portal_cliente_muestra_la_bolsa(): void
    {
        $project = $this->crearBolsa();

        $response = $this->withSession(['portal_cliente_id' => $project->client_id])
            ->get(route('portal.cliente.dashboard'));

        $response->assertOk();
        $response->assertSee('Bolsa de horas prepagada');
        $response->assertSee('Horas contratadas');
        $response->assertSee('Modulo de reportes');
        $response->assertSee('Ajustes checkout');
    }

    public function test_admin_registra_horas_en_la_bolsa(): void
    {
        Role::create(['name' => 'admin']);
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $project = $this->crearBolsa();

        $response = $this->actingAs($admin)->post(
            route('admin.internal-projects.movimientos.store', $project),
            ['fecha' => '2026-08-10', 'descripcion' => 'Optimizacion queries', 'horas' => 3]
        );

        $response->assertRedirect(route('admin.internal-projects.show', $project));
        $this->assertDatabaseHas('bolsa_movimientos', [
            'internal_project_id' => $project->id,
            'descripcion' => 'Optimizacion queries',
            'horas' => 3,
        ]);
        $this->assertEqualsWithDelta(10.5, $project->fresh()->load('bolsaMovimientos')->horas_consumidas, 0.001);
    }

    public function test_admin_form_crear_muestra_seccion_bolsa(): void
    {
        Role::create(['name' => 'admin']);
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $response = $this->actingAs($admin)->get(route('admin.internal-projects.create'));

        $response->assertOk();
        $response->assertSee('Bolsa de horas prepagada');
        $response->assertSee('Total de horas contratadas');
        $response->assertSee('Puntos acordados');
    }

    public function test_admin_detalle_muestra_pestana_bolsa(): void
    {
        Role::create(['name' => 'admin']);
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $project = $this->crearBolsa();

        $response = $this->actingAs($admin)->get(route('admin.internal-projects.show', $project));

        $response->assertOk();
        $response->assertSee('Bitácora de consumo');
        $response->assertSee('Setup inicial');
        $response->assertSee('Registrar horas consumidas');
    }

    public function test_bolsa_calcula_precio_desde_horas_y_valor(): void
    {
        Role::create(['name' => 'admin']);
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $this->actingAs($admin)->post(route('admin.internal-projects.store'), [
            'nombre' => 'Bolsa auto precio',
            'cliente_nombre' => 'Cliente Z',
            'fuente' => 'directo',
            'precio' => 0,
            'moneda' => 'USD',
            'estado' => 'en_progreso',
            'desarrollador_moneda' => 'COP',
            'es_bolsa_horas' => '1',
            'horas_totales' => 10,
            'valor_hora' => 13.5,
        ]);

        $project = InternalProject::where('nombre', 'Bolsa auto precio')->first();
        $this->assertNotNull($project);
        $this->assertEqualsWithDelta(135.0, (float) $project->precio, 0.001);
        $this->assertEqualsWithDelta(13.5, (float) $project->valor_hora, 0.001);
    }

    public function test_horas_no_bolsa_no_se_guardan(): void
    {
        Role::create(['name' => 'admin']);
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $response = $this->actingAs($admin)->post(route('admin.internal-projects.store'), [
            'nombre' => 'Proyecto normal',
            'cliente_nombre' => 'Alguien',
            'fuente' => 'directo',
            'precio' => 1000000,
            'moneda' => 'COP',
            'estado' => 'cotizado',
            'fecha_entrega' => '2026-09-01',
            'desarrollador_moneda' => 'COP',
            // sin es_bolsa_horas
        ]);

        $project = InternalProject::where('nombre', 'Proyecto normal')->first();
        $this->assertNotNull($project);
        $this->assertFalse((bool) $project->es_bolsa_horas);
        $this->assertNull($project->horas_totales);
        $this->assertNull($project->puntos_acuerdo);
    }
}
