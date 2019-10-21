<?php

return [
    'api' => [
        'url' => 'https://api.supermetrics.com/assignment/',
        'actions' => [
            'token' => [
                'method' => 'post',
                'action' => 'register',
                'params' => [
                    'client_id' => 'your_client_id',
                    'email' => 'your@email.address',
                    'name' => 'Your Name',
                ],
            ],
            'posts' => [
                'method' => 'get',
                'action' => 'posts',
            ],
        ],
    ],
    'pages' => 10,
];