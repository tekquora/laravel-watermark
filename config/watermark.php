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

    // 'views' => [
    //     /*
    //     | This MUST be a Blade layout that has @yield('content')
    //     | NOT a Blade component like <x-app-layout>
    //     */
    //     'layout' => 'layouts.app',
    // ],

    'views' => [
        /*
        | Layout can be:
        | - Blade view string
        | - Callable that returns blade view
        */
        'layout' => null,
    ],

];
