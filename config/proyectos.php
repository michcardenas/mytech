<?php

return [

    /*
    |--------------------------------------------------------------------------
    | API key de proyectos
    |--------------------------------------------------------------------------
    | Token secreto para autenticar la API REST de proyectos (crear/leer/editar
    | vía IA). Se envía en el header "Authorization: Bearer <key>" o "X-API-Key".
    | Si está vacío, la API responde 401 a todo (fail-safe).
    */
    'api_key' => env('PROYECTOS_API_KEY'),

];
