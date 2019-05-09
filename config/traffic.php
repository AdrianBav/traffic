<?php

use Illuminate\Support\Str;

return [

    /*
    |--------------------------------------------------------------------------
    | Master Switch
    |--------------------------------------------------------------------------
    |
    | This option may be used to disable the recording of visits.
    |
    */

    'enabled' => env('TRAFFIC_ENABLED', true),

    /*
    |--------------------------------------------------------------------------
    | Site Slug
    |--------------------------------------------------------------------------
    |
    | Define a unique site slug for use in recording web traffic.
    |
    */

    'site_slug' => Str::slug(env('TRAFFIC_SITE_SLUG', env('APP_NAME', 'Unknown Site')), '_'),

    /*
    |--------------------------------------------------------------------------
    | Default Database Connection Name
    |--------------------------------------------------------------------------
    |
    | Here you may specify which of the database connections below you wish
    | to use as your default connection for all database work.
    |
    | Use 'app-default' to use the main applications default connection.
    |
    */

    'database_default' => env('TRAFFIC_DATABASE_DEFAULT', 'app-default'),

    /*
    |--------------------------------------------------------------------------
    | Database Connections
    |--------------------------------------------------------------------------
    |
    | Here are each of the database connections setup for this package.
    |
    | All database work in Laravel is done through the PHP PDO facilities
    | so make sure you have the driver for your particular database of
    | choice installed on your machine before you begin development.
    |
    */

    'database_connections' => [

        'testing' => [
            'driver' => 'sqlite',
            'url' => '',
            'database' => ':memory:',
            'prefix' => '',
            'foreign_key_constraints' => true,
        ],

        'mysql' => [
            'driver' => 'mysql',
            'url' => env('TRAFFIC_DATABASE_URL'),
            'host' => env('TRAFFIC_DB_HOST', '127.0.0.1'),
            'port' => env('TRAFFIC_DB_PORT', '3306'),
            'database' => env('TRAFFIC_DB_DATABASE', 'forge'),
            'username' => env('TRAFFIC_DB_USERNAME', 'forge'),
            'password' => env('TRAFFIC_DB_PASSWORD', ''),
            'unix_socket' => env('TRAFFIC_DB_SOCKET', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,
            'options' => extension_loaded('pdo_mysql') ? array_filter([
                PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
            ]) : [],
        ],

    ],

];
