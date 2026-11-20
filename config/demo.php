<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Mode démonstration
    |--------------------------------------------------------------------------
    |
    | Active les comptes de démonstration, les boutons de connexion directe,
    | la bannière et la commande `demo:reset`. À laisser à false en local si
    | l'on ne veut pas de ces raccourcis.
    |
    */

    'enabled' => (bool) env('DEMO_MODE', false),

    'password' => env('DEMO_PASSWORD', 'Demo1234!'),

    'accounts' => [
        'admin' => 'admin@demo.certifs.test',
        'superviseur' => 'superviseur@demo.certifs.test',
        'participant' => 'participant@demo.certifs.test',
    ],
];
