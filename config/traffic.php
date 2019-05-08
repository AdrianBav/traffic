<?php

use Illuminate\Support\Str;

return [

    /*
    |--------------------------------------------------------------------------
    | Site Slug
    |--------------------------------------------------------------------------
    |
    | Define a unique site slug for use in recording web traffic.
    |
    */

    'site_slug' => Str::slug(env('TRAFFIC_SITE_SLUG', env('APP_NAME', 'Unknown Site')), '_'),

];
