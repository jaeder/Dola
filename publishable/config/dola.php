<?php

return [
    /*
    |--------------------------------------------------------------------------
    | User config
    |--------------------------------------------------------------------------
    |
    | Here you can specify dola user configs
    |
    */

    'user' => [
        'add_default_role_on_register' => true,
        'default_role'                 => 'user',
        'namespace'                    => App\User::class,
        'default_avatar'               => 'users/default.png',
    ],

    /*
    |--------------------------------------------------------------------------
    | Controllers config
    |--------------------------------------------------------------------------
    |
    | Here you can specify dola controller settings
    |
    */

    'controllers' => [
        'namespace' => 'DFZ\\Dola\\Http\\Controllers',
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
        //'namespace' => 'App\\',
    ],

    /*
    |--------------------------------------------------------------------------
    | Path to the Dola Assets
    |--------------------------------------------------------------------------
    |
    | Here you can specify the location of the dola assets path
    |
    */

    'assets_path' => '/vendor/dfz/dola/assets',

    /*
    |--------------------------------------------------------------------------
    | Storage Config
    |--------------------------------------------------------------------------
    |
    | Here you can specify attributes related to your application file system
    |
    */

    'storage' => [
        'disk' => 'public',
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
    | Here you can specify dola database settings
    |
    */

    'database' => [
        'tables' => [
            'hidden' => ['migrations', 'data_rows', 'data_types', 'menu_items', 'password_resets', 'permission_role', 'permissions', 'settings'],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Multilingual configuration
    |--------------------------------------------------------------------------
    |
    | Here you can specify if you want Dola to ship with support for
    | multilingual and what locales are enabled.
    |
    */

    'multilingual' => [
        /*
         * Set whether or not the multilingual is supported by the BREAD input.
         */
        'enabled' => false,

        /*
         * Select default language
         */
        'default' => 'en',

        /*
         * Select languages that are supported.
         */
        'locales' => [
            'en',
            //'pt',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Widgets Config
    |--------------------------------------------------------------------------
    |
    | Here you can specify dola administration settings
    |
    */

    'widgets' => [
        [
            'name'  => 'User',
            'icon'  => 'dola-group',
            'model' => DFZ\Dola\Models\User::class,
            'url'   => 'admin/users',
            'image' => '/images/widget-backgrounds/02.png',
        ],
        [
            'name'  => 'Post',
            'icon'  => 'dola-news',
            'model' => DFZ\Dola\Models\Post::class,
            'url'   => 'admin/posts',
            'image' => '/images/widget-backgrounds/03.png',
        ],
        [
            'name'  => 'Page',
            'icon'  => 'dola-file-text',
            'model' => DFZ\Dola\Models\Page::class,
            'url'   => 'admin/pages',
            'image' => '/images/widget-backgrounds/04.png',
        ],
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
            'Profile' => [
                'route'         => 'dola.profile',
                'classes'       => 'class-full-of-rum',
                'icon_class'    => 'dola-person',
            ],
            'Home' => [
                'route'         => '/',
                'icon_class'    => 'dola-home',
                'target_blank'  => true,
            ],
            'Logout' => [
                'route'      => 'dola.logout',
                'icon_class' => 'dola-power',
            ],
        ],

        'data_tables' => [
            'responsive' => true, // Use responsive extension for jQuery dataTables that are not server-side paginated
        ],

        'widgets' => [
            'DFZ\\Dola\\Widgets\\UserDimmer',
            'DFZ\\Dola\\Widgets\\PostDimmer',
            'DFZ\\Dola\\Widgets\\PageDimmer',
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | UI Generic Config
    |--------------------------------------------------------------------------
    |
    | Here you change some of the Dola UI settings.
    |
    | TODO: Move style properties to assets/css
    |
    */

    'login' => [
        'gradient_a' => '#ffffff',
        'gradient_b' => '#ffffff',
    ],

    'primary_color' => '#22A7F0',

    'show_dev_tips' => true, // Show development tip "How To Use:" in Menu and Settings

    // Here you can specify additonal assets you would like to be included in the master.blade
    'additional_css' => [
        //'css/custom.css',
    ],

    'additional_js' => [
        //'js/custom.js',
    ],

];
