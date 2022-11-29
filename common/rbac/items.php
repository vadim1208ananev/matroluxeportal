<?php
return [
    'createOrder' => [
        'type' => 2,
        'description' => 'Create order',
    ],
    'viewOrder' => [
        'type' => 2,
        'description' => 'View order',
    ],
    'viewOwnOrder' => [
        'type' => 2,
        'description' => 'View own order',
        'ruleName' => 'isAuthor',
        'children' => [
            'viewOrder',
        ],
    ],
    'createSpec' => [
        'type' => 2,
        'description' => 'Create spec',
    ],
    'viewSpec' => [
        'type' => 2,
        'description' => 'View spec',
    ],
    'viewOwnSpec' => [
        'type' => 2,
        'description' => 'View own spec',
        'ruleName' => 'isAuthor',
        'children' => [
            'viewSpec',
        ],
    ],
    'viewBackend' => [
        'type' => 2,
        'description' => 'View backend',
    ],
    'viewMap' => [
        'type' => 2,
        'description' => 'View map',
    ],
    'author' => [
        'type' => 1,
        'children' => [
            'createOrder',
            'viewOwnOrder',
            'createSpec',
            'viewOwnSpec',
        ],
    ],
    'admin' => [
        'type' => 1,
        'children' => [
            'viewOrder',
            'viewSpec',
            'author',
            'backend',
            'map',
        ],
    ],
    'backend' => [
        'type' => 1,
        'children' => [
            'viewBackend',
        ],
    ],
    'map' => [
        'type' => 1,
        'children' => [
            'viewMap',
        ],
    ],
];
