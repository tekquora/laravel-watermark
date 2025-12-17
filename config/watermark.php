<?php

return [

    'route' => [
        'enabled' => true,

        // Default admin prefix
        'prefix' => 'admin/watermark',

        // Default middleware
        'middleware' => ['web', 'auth'],

        // Route name prefix
        'name_prefix' => 'watermark.',

        /*
        | Optional override
        | Host app can mount routes anywhere
        */
        'custom_routes' => null,
    ],

     /*
    |--------------------------------------------------------------------------
    | Layout Configuration (EXPLICIT)
    |--------------------------------------------------------------------------
    |
    | type:
    |   - blade     => classic @extends layout
    |   - component => Blade component layout (<x-*>)
    |   - none      => no layout
    |
    */

    'layout' => [
        'type' => 'blade', // blade | component | none

        // For blade
        'view' => 'layouts.app',

        // For component
        'component' => 'app-layout',
    ],

];
