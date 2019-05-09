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
    | Database Connection
    |--------------------------------------------------------------------------
    |
    | Set the database connection to use, to store visit data.
    |
    */

    'db_connection' => env('TRAFFIC_DB_CONNECTION', 'mysql'),

];
