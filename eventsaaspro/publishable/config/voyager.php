<?php

return [
    /*
    |--------------------------------------------------------------------------
    | User config
    |--------------------------------------------------------------------------
    |
    | Here you can specify voyager user configs
    |
    */

    'user' => [
        'add_default_role_on_register' => true,
        'default_role'                 => 'user',
        // Set `namespace` to `null` to use `config('auth.providers.users.model')` value
        // Set `namespace` to a class to override auth user model.
        // However make sure the appointed class must ready to use before installing voyager.
        // Otherwise `php artisan voyager:install` will fail with class not found error.
        'namespace'                    => null,
        'default_avatar'               => 'users/default.png',
        'redirect'                     => '/',
    ],

    /*
    |--------------------------------------------------------------------------
    | Controllers config
    |--------------------------------------------------------------------------
    |
    | Here you can specify voyager controller settings
    |
    */

    'controllers' => [
        'namespace' => 'Eventsaaspro\\Http\\Controllers\\Voyager',
    ],

    /*
    |--------------------------------------------------------------------------
    | Models config
    |--------------------------------------------------------------------------
    |
    | Here you can specify default model namespace when creating BREAD.
    | Must include trailing backslashes. If not defined the default application
    | namespace will be used.
    |
    */

    'models' => [
        'namespace' => 'Eventsaaspro\\Models\\',
    ],

    /*
    |--------------------------------------------------------------------------
    | Storage Config
    |--------------------------------------------------------------------------
    |
    | Here you can specify attributes related to your application file system
    |
    */

    'storage' => [
        'disk' => env('FILESYSTEM_DRIVER', 'public'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Media Manager
    |--------------------------------------------------------------------------
    |
    | Here you can specify if media manager can show hidden files like(.gitignore)
    |
    */

    'hidden_files' => false,

    /*
    |--------------------------------------------------------------------------
    | Database Config
    |--------------------------------------------------------------------------
    |
    | Here you can specify voyager database settings
    |
    */

    'database' => [
        'tables' => [
            'hidden' => ['migrations', 'data_rows', 'data_types', 'menu_items', 'password_resets', 'permission_role', 'settings'],
        ],
        'autoload_migrations' => false,
    ],

    /*
    |--------------------------------------------------------------------------
    | Multilingual configuration
    |--------------------------------------------------------------------------
    |
    | Here you can specify if you want Voyager to ship with support for
    | multilingual and what locales are enabled.
    |
    */

    'multilingual' => [
        /*
         * Set whether or not the multilingual is supported by the BREAD input.
         */
        'enabled' => true,

        /*
         * Select default language
         */
        'default' => 'en',

        /*
         * Select languages that are supported.
         */
        'locales' => config('eventsaaspro.locales'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Dashboard config
    |--------------------------------------------------------------------------
    |
    | Here you can modify some aspects of your dashboard
    |
    */

    'dashboard' => [
        // Add custom list items to navbar's dropdown
        'navbar_items' => [
            'voyager::generic.profile' => [
                'route'      => 'voyager.profile',
                'classes'    => 'class-full-of-rum',
                'icon_class' => 'voyager-person',
            ],
            'voyager::generic.Website' => [
                'route'        => 'eventsaaspro.welcome',
                'icon_class'   => 'voyager-home',
            ],
            'voyager::generic.logout' => [
                'route'      => 'voyager.logout',
                'icon_class' => 'voyager-power',
            ],
        ],

        'widgets' => [
            'Eventsaaspro\\Widgets\\TotalEvents',
            'Eventsaaspro\\Widgets\\TotalBookings',
            'Eventsaaspro\\Widgets\\TotalRevenue',
            'Eventsaaspro\\Widgets\\TotalUsers',
            'Eventsaaspro\\Widgets\\Notifications',
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Automatic Procedures
    |--------------------------------------------------------------------------
    |
    | When a change happens on Voyager, we can automate some routines.
    |
    */

    'bread' => [
        // When a BREAD is added, create the Menu item using the BREAD properties.
        'add_menu_item' => true,

        // which menu add item to
        'default_menu' => 'admin',

        // When a BREAD is added, create the related Permission.
        'add_permission' => true,

        // which role add premissions to
        'default_role' => 'admin',
    ],

    /*
    |--------------------------------------------------------------------------
    | UI Generic Config
    |--------------------------------------------------------------------------
    |
    | Here you change some of the Voyager UI settings.
    |
    */

    'primary_color' => '#2176FF',

    'show_dev_tips' => env('EVENTMIE_PKG_DEV', false) && env('DEMO_MODE', false) ? true : false, // Show development tip "How To Use:" in Menu and Settings

    // Here you can specify additional assets you would like to be included in the master.blade
    'additional_css' => [
        'custom_theme'  => '',
    ],

    'additional_js' => [
        // 'jquery'   =>  "//ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js",
        // 'raphael'  =>  "//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js",
        // 'morris'   =>  "//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"
    ],

    'googlemaps' => [
         'key'    => env('GOOGLE_MAPS_KEY', ''),
         'center' => [
             'lat' => env('GOOGLE_MAPS_DEFAULT_CENTER_LAT', '32.715738'),
             'lng' => env('GOOGLE_MAPS_DEFAULT_CENTER_LNG', '-117.161084'),
         ],
         'zoom' => env('GOOGLE_MAPS_DEFAULT_ZOOM', 11),
     ],

    /*
    |--------------------------------------------------------------------------
    | Model specific settings
    |--------------------------------------------------------------------------
    |
    | Here you change some model specific settings
    |
    */

    'settings' => [
        // Enables Laravel cache method for
        // storing cache values between requests
        'cache' => false,
    ],

    // Activate compass when environment is NOT local
    'compass_in_production' => env('EVENTMIE_PKG_DEV', false),

    'media' => [
        // The allowed mimetypes to be uploaded through the media-manager.
        // 'allowed_mimetypes' => '*', //All types can be uploaded

        'allowed_mimetypes' => [
            'image/jpeg',
            'image/jp',
            'image/png',
            'image/gif',
            'image/bmp',
            'video/mp4',
            'application/pdf',
        ],

       //Path for media-manager. Relative to the filesystem.
       'path'                => '/',
       'show_folders'        => true,
       'allow_upload'        => true,
       'allow_move'          => true,
       'allow_delete'        => true,
       'allow_create_folder' => true,
       'allow_rename'        => true,
       /*'watermark'           => [
            'source'         => 'watermark.png',
            'position'       => 'bottom-left',
            'x'              => 0,
            'y'              => 0,
            'size'           => 15,
       ],
       'thumbnails'          => [
           [
                'type'  => 'fit',
                'name'  => 'fit-500',
                'width' => 500,
                'height'=> 500
           ],
       ]*/
   ],

   'pkg_dev_mode'       => env('EVENTMIE_PKG_DEV', 0),
   'demo_mode'          => env('DEMO_MODE', 0),
];
