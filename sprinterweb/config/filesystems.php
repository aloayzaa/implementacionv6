<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default filesystem disk that should be used
    | by the framework. The "local" disk, as well as a variety of cloud
    | based disks are available to your application. Just store away!
    |
    */

    'default' => env('FILESYSTEM_DRIVER', 'local'),

    /*
    |--------------------------------------------------------------------------
    | Default Cloud Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Many applications store files both locally and in the cloud. For this
    | reason, you may specify a default "cloud" driver here. This driver
    | will be bound as the Cloud disk implementation in the container.
    |
    */

    'cloud' => env('FILESYSTEM_CLOUD', 's3'),

    /*
    |--------------------------------------------------------------------------
    | Filesystem Disks
    |--------------------------------------------------------------------------
    |
    | Here you may configure as many filesystem "disks" as you wish, and you
    | may even configure multiple disks of the same driver. Defaults have
    | been setup for each driver as an example of the required options.
    |
    | Supported Drivers: "local", "ftp", "sftp", "s3", "rackspace"
    |
    */

    'disks' => [

        'local' => [
            'driver' => 'local',
            'root' => storage_path('app'),
        ],

        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
        ],

        's3' => [
            'driver' => 's3',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION'),
            'bucket' => env('AWS_BUCKET'),
            'url' => env('AWS_URL'),
        ],

        'comprobantes' => [
            'driver' => 'local',
            'root' => public_path('comprobantes'),
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
        ],

        'images' => [
            'driver' => 'local',
            'root' => public_path('images'),
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
        ],

        'logos' => [
            'driver' => 'local',
            'root' => public_path('images/logos'),
            'visibility' => 'public',
        ],

        'ples' => [
            'driver' => 'local',
            'root' => public_path('ples'),
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
        ],

        /* CUANDO SE GUARDABA EN EL PROYECTO
        'FIRMAXML' => [
            'driver' => 'local',
            'root' => public_path('FIRMAXML'),
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
        ],
        */

        // STORAGE DIGITAL OCEAN
        'FIRMAXML' => [
            'driver' => 's3',
            'key' => env('FIRMAXML_ACCESS_KEY_ID'),
            'secret' => env('FIRMAXML_SECRET_ACCESS_KEY'),
            'region' => env('FIRMAXML_DEFAULT_REGION'),
            'bucket' => env('FIRMAXML_BUCKET'),
            'endpoint' => env('FIRMAXML_ENDPOINT'),
        ],

        /* CUANDO SE GUARDABA EN EL PROYECTO
        'SUNATXML' => [
            'driver' => 'local',
            'root' => public_path('SUNATXML'),
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
        ],
        */

        // STORAGE DIGITAL OCEAN
        'SUNATXML' => [
            'driver' => 's3',
            'key' => env('SUNATXML_ACCESS_KEY_ID'),
            'secret' => env('SUNATXML_SECRET_ACCESS_KEY'),
            'region' => env('SUNATXML_DEFAULT_REGION'),
            'bucket' => env('SUNATXML_BUCKET'),
            'endpoint' => env('SUNATXML_ENDPOINT'),
        ],

    ],

];
