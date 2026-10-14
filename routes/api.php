<?php

use App\Http\Controllers\Api\ClientApiController;
use App\Http\Controllers\Api\DeveloperApiController;
use App\Http\Controllers\Api\InternalProjectApiController;
use App\Http\Controllers\Api\ProyectoApiController;
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
});
