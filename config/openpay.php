<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Openpay Configuration
    |--------------------------------------------------------------------------
    |
    | Configuración para la integración con Openpay
    | Obtén tus credenciales desde el Dashboard de Openpay
    |
    */

    // ID del comercio (MERCHANT_ID)
    'merchant_id' => env('OPENPAY_MERCHANT_ID', ''),

    // Llave privada (PRIVATE_API_KEY)
    'private_key' => env('OPENPAY_PRIVATE_KEY', ''),

    // Llave pública (PUBLIC_API_KEY) - para JavaScript
    'public_key' => env('OPENPAY_PUBLIC_KEY', ''),

    // Modo sandbox (true para pruebas, false para producción)
    'sandbox' => env('OPENPAY_SANDBOX', true),

    // URLs de la API
    'api_url' => [
        'sandbox' => 'https://sandbox-api.openpay.mx',
        'production' => 'https://api.openpay.mx',
    ],

    // URLs del Dashboard
    'dashboard_url' => [
        'sandbox' => 'https://sandbox-dashboard.openpay.mx',
        'production' => 'https://dashboard.openpay.mx',
    ],

    // Configuración por defecto para checkouts
    'defaults' => [
        'currency' => 'MXN',
        'send_email' => false,
        'expiration_days' => 7,
        'customer' => [
            'name' => 'Cliente',
            'last_name' => 'Openpay',
            'phone_number' => '5555555555',
            'email' => 'cliente@ejemplo.com',
        ],
    ],

    // Planes de pago sin intereses
    'payment_plans' => [
        'enabled' => env('OPENPAY_MSI_ENABLED', false),
        'payments' => '3,6', // 3, 6, 9, 12 meses disponibles
        'payments_type' => 'WITHOUT_INTEREST',
    ],
];