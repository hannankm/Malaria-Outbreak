<?php

return [
    // Permissions grouped by feature
    'permissions' => [
        'household' => [
            'view',
            'create',
            'edit',
            'delete',
        ],
        'region' => [
            'view',
            'create',
            'edit',
            'delete',
        ],
        'woreda' => [
            'view',
            'create',
            'edit',
            'delete',
        ],
        'zone' => [
            'view',
            'create',
            'edit',
            'delete',
        ],
        'dashboard' => [
            'view region',
            'view federal'
        ],
        'approve account' => [
            'supervisor',
            'reg admin'
        ]
        // You can add other permission groups as needed
    ],

    // Roles and their associated permissions
    'roles' => [
        'super_admin' => [
            'household' => ['view', 'create', 'edit', 'delete'],
            'region' => ['view', 'create', 'edit', 'delete'],
            'woreda' => ['view', 'create', 'edit', 'delete'],
            'zone' => ['view', 'create', 'edit', 'delete'],
            'user' => ['view', 'create', 'edit', 'delete'],
            'dashboard' => ['view region', 'view federal'],
            'approve account' => ['supervisor','reg admin' ],
        ],
        'region_admin' => [
            'woreda' => ['view', 'create', 'edit', 'delete'],
            'zone' => ['view', 'create', 'edit', 'delete'],
            'user' => ['view', 'create', 'edit', 'delete'],
            'dashboard' => ['view region'],
            'approve account' => ['supervisor'],
        ],
        'supervisor' => [
            'household' => ['view', 'create', 'edit', 'delete'],
        ],
        // Add other roles as needed
    ]
];