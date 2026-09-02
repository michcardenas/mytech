<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Twitter / X handle de la marca
    |--------------------------------------------------------------------------
    | Se emite como <meta name="twitter:site"> y twitter:creator.
    | Déjalo en null si aún no tienes cuenta de X — así no se renderiza nada
    | incorrecto. Formato: '@mytechsolutions'.
    */
    'twitter_handle' => env('SEO_TWITTER_HANDLE', null),

    /*
    |--------------------------------------------------------------------------
    | Dimensiones por defecto del OG image
    |--------------------------------------------------------------------------
    | Facebook/LinkedIn/WhatsApp renderizan la card grande al instante cuando
    | conocen el tamaño. Estándar recomendado: 1200×630.
    */
    'og_image_width' => env('SEO_OG_IMAGE_WIDTH', 1200),
    'og_image_height' => env('SEO_OG_IMAGE_HEIGHT', 630),

    /*
    |--------------------------------------------------------------------------
    | Proyectos excluidos del índice de Google (noindex,follow)
    |--------------------------------------------------------------------------
    | Casos de portafolio que atraen tráfico de marca ajena ("basura") sin
    | intención comercial y que ensucian las métricas de Search Console. Se
    | sirven igual en el sitio (siguen enlazados y pasan link equity), pero se
    | marcan noindex para que Google no los muestre ni los cuente. Quita un slug
    | de esta lista para volver a indexar ese proyecto.
    */
    'noindex_proyecto_slugs' => [
        'onlyescorts',
        'bingo-riffy',
    ],

];
