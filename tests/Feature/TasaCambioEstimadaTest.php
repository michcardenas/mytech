<?php

namespace Tests\Feature;

use App\Models\InternalProject;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class TasaCambioEstimadaTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        Role::findOrCreate('admin');
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        return $admin;
    }

    public function test_accessor_convierte_precio_a_cop_con_la_tasa(): void
    {
        $project = new InternalProject(['precio' => 1000, 'moneda' => 'USD', 'tasa_cambio_estimada' => 4050]);
        $this->assertEqualsWithDelta(4050000.0, (float) $project->precio_cop_estimado, 0.001);

        $cop = new InternalProject(['precio' => 5000000, 'moneda' => 'COP', 'tasa_cambio_estimada' => 4050]);
        $this->assertNull($cop->precio_cop_estimado);

        $sinTasa = new InternalProject(['precio' => 1000, 'moneda' => 'USD', 'tasa_cambio_estimada' => null]);
        $this->assertNull($sinTasa->precio_cop_estimado);
    }

    public function test_store_guarda_la_tasa_en_proyecto_usd(): void
    {
        $this->actingAs($this->admin())->post(route('admin.internal-projects.store'), [
            'nombre' => 'Proyecto USD',
            'cliente_nombre' => 'Cliente USD',
            'fuente' => 'directo',
            'precio' => 1500,
            'moneda' => 'USD',
            'tasa_cambio_estimada' => 4100,
            'estado' => 'en_progreso',
            'fecha_entrega' => '2026-12-01',
            'desarrollador_moneda' => 'COP',
        ]);

        $project = InternalProject::where('nombre', 'Proyecto USD')->first();
        $this->assertNotNull($project);
        $this->assertEqualsWithDelta(4100.0, (float) $project->tasa_cambio_estimada, 0.001);
        $this->assertEqualsWithDelta(6150000.0, (float) $project->precio_cop_estimado, 0.001);
    }

    public function test_store_ignora_la_tasa_si_la_moneda_es_cop(): void
    {
        $this->actingAs($this->admin())->post(route('admin.internal-projects.store'), [
            'nombre' => 'Proyecto COP',
            'cliente_nombre' => 'Cliente COP',
            'fuente' => 'directo',
            'precio' => 5000000,
            'moneda' => 'COP',
            'tasa_cambio_estimada' => 4100,
            'estado' => 'cotizado',
            'fecha_entrega' => '2026-12-01',
            'desarrollador_moneda' => 'COP',
        ]);

        $project = InternalProject::where('nombre', 'Proyecto COP')->first();
        $this->assertNotNull($project);
        $this->assertNull($project->tasa_cambio_estimada);
    }

    public function test_form_crear_muestra_el_campo_de_tasa(): void
    {
        $response = $this->actingAs($this->admin())->get(route('admin.internal-projects.create'));

        $response->assertOk();
        $response->assertSee('Tasa de cambio estimada');
        $response->assertSee('tasa_cambio_estimada');
    }

    public function test_detalle_muestra_el_precio_estimado_en_cop(): void
    {
        $project = InternalProject::create([
            'nombre' => 'Proyecto USD detalle',
            'cliente_nombre' => 'Cliente USD',
            'fuente' => 'directo',
            'precio' => 1000,
            'moneda' => 'USD',
            'tasa_cambio_estimada' => 4100,
            'estado' => 'en_progreso',
        ]);

        $response = $this->actingAs($this->admin())->get(route('admin.internal-projects.show', $project));

        $response->assertOk();
        $response->assertSee('4.100.000'); // 1000 USD x 4100 = COP estimado
        $response->assertSee('data-tasa', false); // el cobro puede prellenar el COP con la tasa
    }
}
