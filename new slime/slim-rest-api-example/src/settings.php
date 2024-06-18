<?php

return [
    'settings' => [
        'displayErrorDetails' => true, // Set to false in production
        'logger' => [
            'name' => 'app',
            'path' => __DIR__ . '/../logs/app.log',
            'level' => \Monolog\Logger::DEBUG,
        ],
        'db' => [
            'host' => 'localhost',
            'dbname' => 'record',
            'user' => 'root',
            'pass' => '',
        ],
    ],
];
