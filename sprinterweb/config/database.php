<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Database Connection Name
    |--------------------------------------------------------------------------
    |
    | Here you may specify which of the database connections below you wish
    | to use as your default connection for all database work. Of course
    | you may use many connections at once using the Database library.
    |
    */

    'default' => 'panel',

    'connections' => [

        'mysql' => [
            'driver' => 'mysql',
            'host' => env('DB_HOST', 'localhost'),
            // 'host' => env('DB_HOST', '192.168.0.17'),
            'port' => env('DB_PORT', '3306'),
            'database' => env('DB_DATABASE', 'forge'),
            'username' => env('DB_USERNAME', 'forge'),
            'password' => env('DB_PASSWORD', ''),
            'unix_socket' => env('DB_SOCKET', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => true,
            'engine' => null,
        ],

        'DB_CONNECTION_SERVICIOS' => [
            'driver' => 'mysql',
            'host' => 'localhost',
            // 'host' => '192.168.0.17',
            'database' => 'servicios',//localhost
            'username' => 'root',
            'password' => '',
            'port' => '3306',
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix' => '',
            'strict' => false,
        ],

        'DB_CONNECTION_10707628338' => [
            'driver'    => 'mysql',
            'host'  => 'localhost',
            // 'host' => '192.168.0.17',
            'database'  => 'nuevosprinter_10707628338',
            'username'  => 'root',
            'password'  => '',
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => '',
            'port'      => '3306',
            'strict'    => false,
        ],


        'DB_CONNECTION_20109072177' => [
            'driver'    => 'mysql',
            'host'  => 'localhost',
            // 'host' => '192.168.0.17',
            'database'  => 'nuevosprinter_20109072177',
            'username'  => 'root',
            'password'  => '',
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => '',
            'port'      => '3306',
            'strict'    => false,
        ],

        'panel' => [
            'driver'    => 'mysql',
            'host' => 'localhost',
            //'host' => '192.168.0.17',
            'database'  => 'panel_sprinter',
            'username'  => 'root',
            'password'  => '',
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => '',
            'port'      => '3306',
            'strict'    => false,
        ],

    ],

    'migrations' => 'migrations',
    'redis' => [
        'client' => 'predis',
        'default' => [
            'host' => env('REDIS_HOST', '127.0.0.1'),
            'password' => env('REDIS_PASSWORD', null),
            'port' => env('REDIS_PORT', 6379),
            'database' => 0,
        ],
    ],
];
