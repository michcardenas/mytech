<?php

use App\Http\Controllers\Api\ClientApiController;
use App\Http\Controllers\Api\DeveloperApiController;
use App\Http\Controllers\Api\InternalProjectApiController;
use App\Http\Controllers\Api\ProjectAreaApiController;
use App\Http\Controllers\Api\ProyectoApiController;
use App\Http\Controllers\Api\TaskApiController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API de proyectos (protegida con API key — header Authorization: Bearer)
|--------------------------------------------------------------------------
| Permite crear / leer / actualizar / borrar proyectos vía JSON, para
| gestionarlos rápido por IA sin usar el formulario del admin.
*/
Route::middleware('api.key')->group(function () {
    Route::get('proyectos', [ProyectoApiController::class, 'index']);
    Route::post('proyectos', [ProyectoApiController::class, 'store']);
    Route::get('proyectos/{idOrSlug}', [ProyectoApiController::class, 'show']);
    Route::match(['put', 'patch'], 'proyectos/{idOrSlug}', [ProyectoApiController::class, 'update']);
    Route::delete('proyectos/{idOrSlug}', [ProyectoApiController::class, 'destroy']);

    // Proyectos internos (gestión interna, /internal-projects)
    Route::get('internal-projects', [InternalProjectApiController::class, 'index']);
    Route::post('internal-projects', [InternalProjectApiController::class, 'store']);
    Route::get('internal-projects/{id}', [InternalProjectApiController::class, 'show'])->whereNumber('id');
    Route::match(['put', 'patch'], 'internal-projects/{id}', [InternalProjectApiController::class, 'update'])->whereNumber('id');
    Route::delete('internal-projects/{id}', [InternalProjectApiController::class, 'destroy'])->whereNumber('id');

    // Clientes (para asignarlos a proyectos via client_id)
    Route::get('clients', [ClientApiController::class, 'index']);
    Route::post('clients', [ClientApiController::class, 'store']);
    Route::get('clients/{id}', [ClientApiController::class, 'show'])->whereNumber('id');
    Route::match(['put', 'patch'], 'clients/{id}', [ClientApiController::class, 'update'])->whereNumber('id');
    Route::delete('clients/{id}', [ClientApiController::class, 'destroy'])->whereNumber('id');

    // Desarrolladores (para asignarlos a proyectos via developer_id)
    Route::get('developers', [DeveloperApiController::class, 'index']);
    Route::post('developers', [DeveloperApiController::class, 'store']);
    Route::get('developers/{id}', [DeveloperApiController::class, 'show'])->whereNumber('id');
    Route::match(['put', 'patch'], 'developers/{id}', [DeveloperApiController::class, 'update'])->whereNumber('id');
    Route::delete('developers/{id}', [DeveloperApiController::class, 'destroy'])->whereNumber('id');

    // Tareas del tablero sobre proyectos existentes
    Route::get('internal-projects/{id}/tasks', [TaskApiController::class, 'index'])->whereNumber('id');
    Route::post('internal-projects/{id}/tasks', [TaskApiController::class, 'store'])->whereNumber('id');
    Route::get('project-tasks/{id}', [TaskApiController::class, 'show'])->whereNumber('id');
    Route::match(['put', 'patch'], 'project-tasks/{id}', [TaskApiController::class, 'update'])->whereNumber('id');
    Route::delete('project-tasks/{id}', [TaskApiController::class, 'destroy'])->whereNumber('id');
    Route::post('project-tasks/{id}/subtasks', [TaskApiController::class, 'storeSubtask'])->whereNumber('id');
    Route::match(['put', 'patch'], 'project-subtasks/{id}', [TaskApiController::class, 'updateSubtask'])->whereNumber('id');
    Route::delete('project-subtasks/{id}', [TaskApiController::class, 'destroySubtask'])->whereNumber('id');

    // Archivos adjuntos por tarea (POST multipart: campo "archivo"[, "nombre", "subido_por"])
    Route::get('project-tasks/{id}/files', [TaskApiController::class, 'indexFiles'])->whereNumber('id');
    Route::post('project-tasks/{id}/files', [TaskApiController::class, 'storeFile'])->whereNumber('id');
    Route::delete('project-task-files/{id}', [TaskApiController::class, 'destroyFile'])->whereNumber('id');

    // Areas / modulos del proyecto (para agrupar y filtrar tareas por tema)
    Route::get('internal-projects/{id}/areas', [ProjectAreaApiController::class, 'index'])->whereNumber('id');
    Route::post('internal-projects/{id}/areas', [ProjectAreaApiController::class, 'store'])->whereNumber('id');
    Route::match(['put', 'patch'], 'project-areas/{id}', [ProjectAreaApiController::class, 'update'])->whereNumber('id');
    Route::delete('project-areas/{id}', [ProjectAreaApiController::class, 'destroy'])->whereNumber('id');
});
