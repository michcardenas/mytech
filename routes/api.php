<?php

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
});
