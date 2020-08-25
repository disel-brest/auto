<?php
return [
    'permAdManage' => [
        'type' => 2,
        'description' => 'CRUD permissions for ads',
    ],
    'permOwnAdManage' => [
        'type' => 2,
        'description' => 'CRUD permissions for own ads',
        'ruleName' => 'isAuthor',
        'children' => [
            'permAdManage',
        ],
    ],
    'permUser' => [
        'type' => 2,
        'description' => 'User permissions',
        'children' => [
            'permOwnAdManage',
        ],
    ],
    'permModerate' => [
        'type' => 2,
        'description' => 'Moderator permissions',
        'children' => [
            'permAdManage',
        ],
    ],
    'permAdmin' => [
        'type' => 2,
        'description' => 'Admin permissions',
    ],
    'waiting' => [
        'type' => 1,
        'description' => 'Waiting for register confirmation',
    ],
    'banned' => [
        'type' => 1,
        'description' => 'Banned User',
    ],
    'user' => [
        'type' => 1,
        'description' => 'User',
        'children' => [
            'permUser',
        ],
    ],
    'moderator' => [
        'type' => 1,
        'description' => 'Moderator',
        'children' => [
            'permModerate',
            'user',
        ],
    ],
    'admin' => [
        'type' => 1,
        'description' => 'Admin',
        'children' => [
            'permAdmin',
            'moderator',
        ],
    ],
];
