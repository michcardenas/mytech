<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\Developer;
use App\Models\InternalProject;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ProjectBoardTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $user = User::create([
            'name' => 'Admin',
            'email' => 'admin@test.local',
            'password' => bcrypt('secret123'),
        ]);
        $user->assignRole('admin');

        return $user;
    }

    private function proyectoConDev(): array
    {
        $client = Client::create(['nombre' => 'Cliente Test']);
        $dev = Developer::create(['nombre' => 'Dev Uno', 'telefono' => '+573001112233']);
        $project = InternalProject::create([
            'nombre' => 'Proyecto Test',
            'cliente_nombre' => 'Cliente Test',
            'client_id' => $client->id,
            'precio' => 1000000,
            'moneda' => 'COP',
            'estado' => 'en_progreso',
            'fuente' => 'directo',
            'developer_id' => $dev->id,
        ]);
        $project->equipo()->attach($dev->id);

        return [$project, $dev];
    }

    public function test_admin_ve_el_tablero(): void
    {
        [$project] = $this->proyectoConDev();

        $this->actingAs($this->admin())
            ->get(route('admin.internal-projects.board', $project))
            ->assertOk()
            ->assertSee('Tablero de tareas');
    }

    public function test_admin_crea_tarea(): void
    {
        [$project, $dev] = $this->proyectoConDev();

        $this->actingAs($this->admin())
            ->post(route('admin.internal-projects.tasks.store', $project), [
                'titulo' => 'Primera tarea',
                'prioridad' => 'alta',
                'developer_id' => $dev->id,
                'columna' => 'por_hacer',
            ])
            ->assertRedirect(route('admin.internal-projects.board', $project));

        $this->assertDatabaseHas('project_tasks', [
            'internal_project_id' => $project->id,
            'titulo' => 'Primera tarea',
            'columna' => 'por_hacer',
            'prioridad' => 'alta',
        ]);
    }

    public function test_admin_mueve_tarea(): void
    {
        [$project] = $this->proyectoConDev();
        $task = $project->tasks()->create(['titulo' => 'T', 'columna' => 'por_hacer']);

        $this->actingAs($this->admin())
            ->postJson(route('admin.project-tasks.move', $task), ['columna' => 'hecho', 'ids' => [$task->id]])
            ->assertOk()
            ->assertJson(['ok' => true, 'columna' => 'hecho']);

        $this->assertEquals('hecho', $task->fresh()->columna);
    }

    public function test_toggle_subtarea(): void
    {
        [$project] = $this->proyectoConDev();
        $task = $project->tasks()->create(['titulo' => 'T', 'columna' => 'por_hacer']);
        $sub = $task->subtasks()->create(['titulo' => 'S']);

        $this->actingAs($this->admin())
            ->postJson(route('admin.project-subtasks.toggle', $sub))
            ->assertOk()
            ->assertJson(['ok' => true, 'hecha' => true]);

        $this->assertTrue($sub->fresh()->hecha);
    }

    public function test_dev_ve_su_tablero_por_portal(): void
    {
        [$project, $dev] = $this->proyectoConDev();

        $this->withSession(['portal_developer_id' => $dev->id])
            ->get(route('portal.developer.board', $project))
            ->assertOk();
    }

    public function test_dev_no_ve_proyecto_ajeno(): void
    {
        [$project, $dev] = $this->proyectoConDev();
        $otro = InternalProject::create([
            'nombre' => 'Ajeno',
            'cliente_nombre' => 'X',
            'precio' => 1,
            'moneda' => 'COP',
            'estado' => 'en_progreso',
            'fuente' => 'directo',
        ]);

        $this->withSession(['portal_developer_id' => $dev->id])
            ->get(route('portal.developer.board', $otro))
            ->assertForbidden();
    }

    public function test_admin_asigna_responsable_a_subtarea(): void
    {
        [$project, $dev] = $this->proyectoConDev();
        $task = $project->tasks()->create(['titulo' => 'T', 'columna' => 'por_hacer']);
        $sub = $task->subtasks()->create(['titulo' => 'S']);

        $this->actingAs($this->admin())
            ->put(route('admin.project-subtasks.update', $sub), ['developer_id' => $dev->id])
            ->assertRedirect();

        $this->assertEquals($dev->id, $sub->fresh()->developer_id);
    }

    public function test_admin_sube_documento_al_proyecto(): void
    {
        Storage::fake('public');
        [$project] = $this->proyectoConDev();

        $this->actingAs($this->admin())
            ->post(route('admin.internal-projects.docs.store', $project), [
                'archivo' => UploadedFile::fake()->create('contrato.pdf', 100, 'application/pdf'),
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('project_files', [
            'internal_project_id' => $project->id,
            'nombre' => 'contrato.pdf',
        ]);
    }

    public function test_admin_adjunta_archivo_a_tarea(): void
    {
        Storage::fake('public');
        [$project] = $this->proyectoConDev();
        $task = $project->tasks()->create(['titulo' => 'T', 'columna' => 'por_hacer']);

        $this->actingAs($this->admin())
            ->post(route('admin.project-tasks.files.store', $task), [
                'archivo' => UploadedFile::fake()->image('mock.png'),
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('project_task_files', [
            'project_task_id' => $task->id,
            'nombre' => 'mock.png',
        ]);
    }

    public function test_dev_adjunta_archivo_a_su_tarea_por_portal(): void
    {
        Storage::fake('public');
        [$project, $dev] = $this->proyectoConDev();
        $task = $project->tasks()->create(['titulo' => 'T', 'columna' => 'por_hacer']);

        $this->withSession(['portal_developer_id' => $dev->id])
            ->post(route('portal.developer.tasks.files.store', $task), [
                'archivo' => UploadedFile::fake()->create('entrega.zip', 50),
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('project_task_files', [
            'project_task_id' => $task->id,
            'subido_por' => $dev->nombre,
        ]);
    }

    public function test_dev_crea_tarea_por_portal(): void
    {
        [$project, $dev] = $this->proyectoConDev();

        $this->withSession(['portal_developer_id' => $dev->id])
            ->post(route('portal.developer.tasks.store', $project), [
                'titulo' => 'Tarea del dev',
                'columna' => 'en_progreso',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('project_tasks', [
            'internal_project_id' => $project->id,
            'titulo' => 'Tarea del dev',
            'columna' => 'en_progreso',
        ]);
    }

    public function test_dev_no_crea_tarea_en_proyecto_ajeno(): void
    {
        [$project, $dev] = $this->proyectoConDev();
        $otro = InternalProject::create([
            'nombre' => 'Ajeno',
            'cliente_nombre' => 'X',
            'precio' => 1,
            'moneda' => 'COP',
            'estado' => 'en_progreso',
            'fuente' => 'directo',
        ]);

        $this->withSession(['portal_developer_id' => $dev->id])
            ->post(route('portal.developer.tasks.store', $otro), ['titulo' => 'Hack'])
            ->assertForbidden();

        $this->assertDatabaseMissing('project_tasks', ['titulo' => 'Hack']);
    }

    public function test_dev_secundario_del_equipo_ve_el_tablero_en_su_dashboard(): void
    {
        [$project] = $this->proyectoConDev();
        $dev2 = Developer::create(['nombre' => 'Dev Dos', 'telefono' => '+573004445566']);
        $project->equipo()->attach($dev2->id);

        $this->withSession(['portal_developer_id' => $dev2->id])
            ->get(route('portal.developer.dashboard'))
            ->assertOk()
            ->assertSee($project->nombre)
            ->assertSee(route('portal.developer.board', $project->id));
    }

    public function test_admin_ve_tablero_general(): void
    {
        [$project] = $this->proyectoConDev();
        $project->tasks()->create(['titulo' => 'Tarea Global A', 'columna' => 'por_hacer']);

        $this->actingAs($this->admin())
            ->get(route('admin.board.global'))
            ->assertOk()
            ->assertSee('Tarea Global A');
    }

    public function test_dev_ve_solo_sus_tareas_en_tablero_general(): void
    {
        [$project, $dev] = $this->proyectoConDev();
        $otro = Developer::create(['nombre' => 'Otro Dev', 'telefono' => '+573009998877']);
        $project->equipo()->attach($otro->id);

        $project->tasks()->create(['titulo' => 'Mi tarea asignada', 'columna' => 'por_hacer', 'developer_id' => $dev->id]);
        $project->tasks()->create(['titulo' => 'Tarea de otro', 'columna' => 'por_hacer', 'developer_id' => $otro->id]);
        $viaSub = $project->tasks()->create(['titulo' => 'Tarea via subtarea', 'columna' => 'por_hacer']);
        $viaSub->subtasks()->create(['titulo' => 'sub mia', 'developer_id' => $dev->id]);

        $resp = $this->withSession(['portal_developer_id' => $dev->id])
            ->get(route('portal.developer.board-global'))
            ->assertOk();

        $resp->assertSee('Mi tarea asignada');
        $resp->assertSee('Tarea via subtarea');
        $resp->assertDontSee('Tarea de otro');
    }

    public function test_admin_guarda_enlaces_del_proyecto(): void
    {
        [$project] = $this->proyectoConDev();

        $this->actingAs($this->admin())
            ->put(route('admin.internal-projects.links.update', $project), [
                'repo_url' => 'https://github.com/mytech/demo',
                'url_produccion' => 'https://demo.com',
                'url_pruebas' => 'https://staging.demo.com',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('internal_projects', [
            'id' => $project->id,
            'repo_url' => 'https://github.com/mytech/demo',
            'url_produccion' => 'https://demo.com',
        ]);
    }
}
