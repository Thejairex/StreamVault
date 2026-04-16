<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Límite de follows por usuario
    |--------------------------------------------------------------------------
    | Máximo de streamers que un usuario puede seguir (y apoyar) al mismo tiempo.
    */
    'max_follows' => env('STREAMVAULT_MAX_FOLLOWS', 5),

    /*
    |--------------------------------------------------------------------------
    | Precio de la suscripción mensual
    |--------------------------------------------------------------------------
    | En USD. Se usa como referencia interna; el valor real lo gestiona Stripe.
    */
    'subscription_price' => env('STREAMVAULT_SUBSCRIPTION_PRICE', 7.99),

    /*
    |--------------------------------------------------------------------------
    | Distribución de ingresos
    |--------------------------------------------------------------------------
    | Porcentaje de la suscripción que se reparte entre los streamers seguidos.
    | El resto queda para la plataforma.
    | Ejemplo: 0.40 = 40% para streamers, 60% para la plataforma.
    */
    'streamer_revenue_share' => env('STREAMVAULT_REVENUE_SHARE', 0.40),

    /*
    |--------------------------------------------------------------------------
    | Umbral mínimo de suscriptores para monetizar
    |--------------------------------------------------------------------------
    | Un streamer necesita al menos este número de seguidores pagadores activos
    | para empezar a recibir distribución de ingresos.
    */
    'min_supporters_to_earn' => env('STREAMVAULT_MIN_SUPPORTERS', 50),

    /*
    |--------------------------------------------------------------------------
    | Proveedor de pagos
    |--------------------------------------------------------------------------
    */
    'payment_provider' => env('STREAMVAULT_PAYMENT_PROVIDER', 'stripe'),

];
