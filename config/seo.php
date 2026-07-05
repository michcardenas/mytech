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

];
