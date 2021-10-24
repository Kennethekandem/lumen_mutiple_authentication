<?php

use App\Models\Admin;
use App\Models\User;

return [
    'defaults' => [
        'guard' => 'api',
        'passwords' => 'users',
    ],
    'guards' => [
        'api' => [
            'driver' => 'passport',
            'provider' => 'users',
        ],
        'admin' => [
            'driver' => 'passport',
            'provider' => 'admins',
        ]
    ],
    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => User::class
        ],
        'admins' => [
            'driver' => 'eloquent',
            'model' => Admin::class
        ]
    ]
];
