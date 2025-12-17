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

    'views' => [
        // Layout to extend
        'layout' => 'layouts.app',
    ],

];
