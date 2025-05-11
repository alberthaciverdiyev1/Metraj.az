<?php

use Nwidart\Modules\Activators\FileActivator;
use Nwidart\Modules\Providers\ConsoleServiceProvider;

return [

    /*
    |--------------------------------------------------------------------------
    | Module Namespace
    |--------------------------------------------------------------------------
    |
    | Default module namespace.
    |
    */
    'namespace' => 'Modules',

    /*
    |--------------------------------------------------------------------------
    | Module Stubs
    |--------------------------------------------------------------------------
    |
    | Default module stubs.
    |
    */
    'stubs' => [
        'enabled' => false,
        'path' => base_path('stubs/nwidart-stubs'),
        'files' => [
            'routes/api' => 'Routes/api.php',
            'scaffold/config' => 'Config/config.php',
            'composer' => 'composer.json',
            'package' => 'package.json',
            'migration' => 'Database/Migrations/' . date('Y_m_d_His') . '_create_$CLASS$_table.php',
            'model' => 'Http/Entities/Model.php',


            //            'routes/web' => 'Routes/web.php',
            //            'views/index' => 'Resources/views/index.blade.php',
            //            'views/includes/cart' => 'Resources/views/includes/cart.blade.php',
            //            'assets/js/app' => 'Resources/assets/js/app.js',
            //            'assets/sass/app' => 'Resources/assets/sass/app.scss',
            //            'vite' => 'vite.config.js',
        ],
        'replacements' => [
            /**
             * Define custom replacements for each section.
             * You can specify a closure for dynamic values.
             *
             * Example:
             *
             * 'composer' => [
             *      'CUSTOM_KEY' => fn (\Nwidart\Modules\Generators\ModuleGenerator $generator) => $generator->getModule()->getLowerName() . '-module',
             *      'CUSTOM_KEY2' => fn () => 'custom text',
             *      'LOWER_NAME',
             *      'STUDLY_NAME',
             *      // ...
             * ],
             *
             * Note: Keys should be in UPPERCASE.
             */
            //            'routes/web' => ['LOWER_NAME', 'STUDLY_NAME', 'PLURAL_LOWER_NAME', 'KEBAB_NAME', 'MODULE_NAMESPACE', 'CONTROLLER_NAMESPACE'],
            'routes/api' => ['LOWER_NAME', 'STUDLY_NAME', 'PLURAL_LOWER_NAME', 'KEBAB_NAME', 'MODULE_NAMESPACE', 'CONTROLLER_NAMESPACE'],
            //            'vite' => ['LOWER_NAME', 'STUDLY_NAME', 'KEBAB_NAME'],
            'json' => ['LOWER_NAME', 'STUDLY_NAME', 'KEBAB_NAME', 'MODULE_NAMESPACE', 'PROVIDER_NAMESPACE'],
            //            'views/index' => ['LOWER_NAME'],
            //            'views/master' => ['LOWER_NAME', 'STUDLY_NAME', 'KEBAB_NAME'],
            'scaffold/config' => ['STUDLY_NAME'],
            'composer' => [
                'LOWER_NAME',
                'STUDLY_NAME',
                'VENDOR',
                'AUTHOR_NAME',
                'AUTHOR_EMAIL',
                'MODULE_NAMESPACE',
                'PROVIDER_NAMESPACE',
                'APP_FOLDER_NAME',
            ],
        ],
        'gitkeep' => true,
    ],
    'paths' => [
        /*
        |--------------------------------------------------------------------------
        | Modules path
        |--------------------------------------------------------------------------
        |
        | This path is used to save the generated module.
        | This path will also be added automatically to the list of scanned folders.
        |
        */
        'modules' => base_path('Modules'),

        /*
        |--------------------------------------------------------------------------
        | Modules assets path
        |--------------------------------------------------------------------------
        |
        | Here you may update the modules' assets path.
        |
        */
        'assets' => public_path('modules'),

        /*
        |--------------------------------------------------------------------------
        | The migrations' path
        |--------------------------------------------------------------------------
        |
        | Where you run the 'module:publish-migration' command, where do you publish the
        | the migration files?
        |
        */
        'migration' => base_path('database/migrations'),

        /*
        |--------------------------------------------------------------------------
        | The app path
        |--------------------------------------------------------------------------
        |
        | app folder name
        | for example can change it to 'src' or 'App'
        */
        'app_folder' => 'app/',

        /*
        |--------------------------------------------------------------------------
        | Generator path
        |--------------------------------------------------------------------------
        | Customise the paths where the folders will be generated.
        | Setting the generate key to false will not generate that folder
        */
        'generator' => [
            // app/
            'actions' => ['path' => 'Actions', 'generate' => false],
            'casts' => ['path' => 'Casts', 'generate' => false],
            'channels' => ['path' => 'Broadcasting', 'generate' => false],
            'class' => ['path' => 'Classes', 'generate' => false],
            'command' => ['path' => 'Console', 'generate' => false],
            'component-class' => ['path' => 'View/Components', 'generate' => false],
            'emails' => ['path' => 'Emails', 'generate' => false],
            'event' => ['path' => 'Events', 'generate' => false],
            'enums' => ['path' => 'Enums', 'generate' => false],
            'exceptions' => ['path' => 'Exceptions', 'generate' => false],
            'jobs' => ['path' => 'Jobs', 'generate' => false],
            'helpers' => ['path' => 'Helpers', 'generate' => false],
            'interfaces' => ['path' => 'Interfaces', 'generate' => false],
            'listener' => ['path' => 'Listeners', 'generate' => false],
            'model' => ['path' => 'Entities', 'generate' => false],
            'notifications' => ['path' => 'Notifications', 'generate' => false],
            'observer' => ['path' => 'Observers', 'generate' => false],
            'policies' => ['path' => 'Policies', 'generate' => false],
            'provider' => ['path' => 'Providers', 'generate' => true],
            'repository' => ['path' => 'Repositories', 'generate' => false],
            'resource' => ['path' => 'Http/Transformers', 'generate' => false],
            'route-provider' => ['path' => 'Providers', 'generate' => true],
            'rules' => ['path' => 'Rules', 'generate' => false],
            'services' => ['path' => 'Services', 'generate' => false],
            'scopes' => ['path' => 'Entities/Scopes', 'generate' => false],
            'traits' => ['path' => 'Traits', 'generate' => false],

            // Http/
            'controller' => ['path' => 'Http/Controllers', 'generate' => true],
            'filter' => ['path' => 'Http/Middleware', 'generate' => false],
            'request' => ['path' => 'Http/Requests', 'generate' => false],

            // config/
            'config' => ['path' => 'Config', 'generate' => true],

            // database/
            'factory' => ['path' => 'Database/Factories', 'generate' => true],
            'migration' => ['path' => 'Database/Migrations', 'generate' => true],
            'seeder' => ['path' => 'Database/Seeders', 'generate' => true],

            // lang/
            'lang' => ['path' => 'Lang', 'generate' => false],

            // resource/
                //            'assets' => ['path' => 'Resources/assets', 'generate' => true],
                //            'component-view' => ['path' => 'Resources/Views/Components', 'generate' => false],
                //            'views' => ['path' => 'Resources/views', 'generate' => true],

            // routes/
            'routes' => ['path' => 'Routes', 'generate' => true],

            // tests/
            'test-feature' => ['path' => 'Tests/Feature', 'generate' => true],
            'test-unit' => ['path' => 'Tests/Unit', 'generate' => true],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Auto Discover of Modules
    |--------------------------------------------------------------------------
    |
    | Here you configure auto discover of module
    | This is useful for simplify module providers.
    |
    */
    'auto-discover' => [
        /*
        |--------------------------------------------------------------------------
        | Migrations
        |--------------------------------------------------------------------------
        |
        | This option for register migration automatically.
        |
        */
        'migrations' => true,

        /*
        |--------------------------------------------------------------------------
        | Translations
        |--------------------------------------------------------------------------
        |
        | This option for register lang file automatically.
        |
        */
        'translations' => false,

    ],

    /*
    |--------------------------------------------------------------------------
    | Package commands
    |--------------------------------------------------------------------------
    |
    | Here you can define which commands will be visible and used in your
    | application. You can add your own commands to merge section.
    |
    */
    'commands' => ConsoleServiceProvider::defaultCommands()
        ->merge([
            // New commands go here
        ])->toArray(),

    /*
    |--------------------------------------------------------------------------
    | Scan Path
    |--------------------------------------------------------------------------
    |
    | Here you define which folder will be scanned. By default will scan vendor
    | directory. This is useful if you host the package in packagist website.
    |
    */
    'scan' => [
        'enabled' => false,
        'paths' => [
            base_path('vendor/*/*'),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Composer File Template
    |--------------------------------------------------------------------------
    |
    | Here is the config for the composer.json file, generated by this package
    |
    */
    'composer' => [
        'vendor' => env('MODULE_VENDOR', 'nwidart'),
        'author' => [
            'name' => env('MODULE_AUTHOR_NAME', 'Nicolas Widart'),
            'email' => env('MODULE_AUTHOR_EMAIL', 'n.widart@gmail.com'),
        ],
        'composer-output' => false,
    ],

    /*
    |--------------------------------------------------------------------------
    | Choose what laravel-modules will register as custom namespaces.
    | Setting one to false will require you to register that part
    | in your own Service Provider class.
    |--------------------------------------------------------------------------
    */
    'register' => [
        'translations' => true,
        /**
         * load files on boot or register method
         */
        'files' => 'register',
    ],

    /*
    |--------------------------------------------------------------------------
    | Activators
    |--------------------------------------------------------------------------
    |
    | You can define new types of activators here, file, database, etc. The only
    | required parameter is 'class'.
    | The file activator will store the activation status in storage/installed_modules
    */
    'activators' => [
        'file' => [
            'class' => FileActivator::class,
            'statuses-file' => base_path('modules_statuses.json'),
        ],
    ],

    'activator' => 'file',
];
