<?php

namespace DFZ\Dola;

use Arrilot\Widgets\Facade as Widget;
use Arrilot\Widgets\ServiceProvider as WidgetServiceProvider;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Intervention\Image\ImageServiceProvider;
use DFZ\Dola\Facades\Dola as DolaFacade;
use DFZ\Dola\FormFields\After\DescriptionHandler;
use DFZ\Dola\Http\Middleware\DolaAdminMiddleware;
use DFZ\Dola\Models\User;
use DFZ\Dola\Translator\Collection as TranslatorCollection;

class DolaServiceProvider extends ServiceProvider
{
    /**
     * Register the application services.
     */
    public function register()
    {
        $this->app->register(ImageServiceProvider::class);
        $this->app->register(WidgetServiceProvider::class);

        $loader = AliasLoader::getInstance();
        $loader->alias('Dola', DolaFacade::class);

        $this->app->singleton('dola', function () {
            return new Dola();
        });

        $this->loadHelpers();

        $this->registerAlertComponents();
        $this->registerFormFields();
        $this->registerWidgets();

        $this->registerConfigs();

        if ($this->app->runningInConsole()) {
            $this->registerPublishableResources();
            $this->registerConsoleCommands();
        }

        if (!$this->app->runningInConsole() || config('app.env') == 'testing') {
            $this->registerAppCommands();
        }
    }

    /**
     * Bootstrap the application services.
     *
     * @param \Illuminate\Routing\Router $router
     */
    public function boot(Router $router, Dispatcher $event)
    {
        if (config('dola.user.add_default_role_on_register')) {
            $app_user = config('dola.user.namespace');
            $app_user::created(function ($user) {
                if (is_null($user->role_id)) {
                    DolaFacade::model('User')->findOrFail($user->id)
                        ->setRole(config('dola.user.default_role'))
                        ->save();
                }
            });
        }

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'dola');

        if (app()->version() >= 5.4) {
            $router->aliasMiddleware('dola.user', DolaAdminMiddleware::class);

            if (config('app.env') == 'testing') {
                $this->loadMigrationsFrom(realpath(__DIR__.'/migrations'));
            }
        } else {
            $router->middleware('dola.user', DolaAdminMiddleware::class);
        }

        $this->registerViewComposers();

        $event->listen('dola.alerts.collecting', function () {
            $this->addStorageSymlinkAlert();
        });

        $this->bootTranslatorCollectionMacros();
    }

    /**
     * Load helpers.
     */
    protected function loadHelpers()
    {
        foreach (glob(__DIR__.'/Helpers/*.php') as $filename) {
            require_once $filename;
        }
    }

    /**
     * Register view composers.
     */
    protected function registerViewComposers()
    {
        // Register alerts
        View::composer('dola::*', function ($view) {
            $view->with('alerts', DolaFacade::alerts());
        });
    }

    /**
     * Add storage symlink alert.
     */
    protected function addStorageSymlinkAlert()
    {
        if (app('router')->current() !== null) {
            $currentRouteAction = app('router')->current()->getAction();
        } else {
            $currentRouteAction = null;
        }
        $routeName = is_array($currentRouteAction) ? array_get($currentRouteAction, 'as') : null;

        if ($routeName != 'dola.dashboard') {
            return;
        }

        if (request()->has('fix-missing-storage-symlink') && !file_exists(public_path('storage'))) {
            $this->fixMissingStorageSymlink();
        } elseif (!file_exists(public_path('storage'))) {
            $alert = (new Alert('missing-storage-symlink', 'warning'))
                ->title('Missing storage symlink')
                ->text('We could not find a storage symlink. This could cause problems with loading media files from the browser.')
                ->button('Fix it', '?fix-missing-storage-symlink=1');

            DolaFacade::addAlert($alert);
        }
    }

    protected function fixMissingStorageSymlink()
    {
        app('files')->link(storage_path('app/public'), public_path('storage'));

        if (file_exists(public_path('storage'))) {
            $alert = (new Alert('fixed-missing-storage-symlink', 'success'))
                ->title('Missing storage symlink created')
                ->text('We just created the missing symlink for you.');
        } else {
            $alert = (new Alert('failed-fixing-missing-storage-symlink', 'danger'))
                ->title('Could not create missing storage symlink')
                ->text('We failed to generate the missing symlink for your application. It seems like your hosting provider does not support it.');
        }

        DolaFacade::addAlert($alert);
    }

    /**
     * Register alert components.
     */
    protected function registerAlertComponents()
    {
        $components = ['title', 'text', 'button'];

        foreach ($components as $component) {
            $class = 'DFZ\\Dola\\Alert\\Components\\'.ucfirst(camel_case($component)).'Component';

            $this->app->bind("dola.alert.components.{$component}", $class);
        }
    }

    protected function bootTranslatorCollectionMacros()
    {
        Collection::macro('translate', function () {
            $transtors = [];

            foreach ($this->all() as $item) {
                $transtors[] = call_user_func_array([$item, 'translate'], func_get_args());
            }

            return new TranslatorCollection($transtors);
        });
    }

    /**
     * Register widget.
     */
    protected function registerWidgets()
    {
        $default_widgets = ['DFZ\\Dola\\Widgets\\UserDimmer', 'DFZ\\Dola\\Widgets\\PostDimmer', 'DFZ\\Dola\\Widgets\\PageDimmer'];
        $widgets = config('dola.dashboard.widgets', $default_widgets);

        foreach ($widgets as $widget) {
            Widget::group('dola::dimmers')->addWidget($widget);
        }
    }

    /**
     * Register the publishable files.
     */
    private function registerPublishableResources()
    {
        $publishablePath = dirname(__DIR__).'/publishable';

        $publishable = [
            'Dola_assets' => [
                "{$publishablePath}/assets/" => public_path(config('dola.assets_path')),
            ],
            'migrations' => [
                "{$publishablePath}/database/migrations/" => database_path('migrations'),
            ],
            'seeds' => [
                "{$publishablePath}/database/seeds/" => database_path('seeds'),
            ],
            'demo_content' => [
                "{$publishablePath}/demo_content/" => storage_path('app/public'),
            ],
            'config' => [
                "{$publishablePath}/config/dola.php" => config_path('dola.php'),
            ],
        ];

        foreach ($publishable as $group => $paths) {
            $this->publishes($paths, $group);
        }
    }

    public function registerConfigs()
    {
        $this->mergeConfigFrom(
            dirname(__DIR__).'/publishable/config/dola.php', 'dola'
        );
    }

    protected function registerFormFields()
    {
        $formFields = [
            'checkbox',
            'date',
            'file',
            'image',
            'multiple_images',
            'number',
            'password',
            'radio_btn',
            'rich_text_box',
            'select_dropdown',
            'select_multiple',
            'text',
            'text_area',
            'timestamp',
            'hidden',
            'code_editor',
        ];

        foreach ($formFields as $formField) {
            $class = studly_case("{$formField}_handler");

            DolaFacade::addFormField("DFZ\\Dola\\FormFields\\{$class}");
        }

        DolaFacade::addAfterFormField(DescriptionHandler::class);

        event('dola.form-fields.registered');
    }

    /**
     * Register the commands accessible from the Console.
     */
    private function registerConsoleCommands()
    {
        $this->commands(Commands\InstallCommand::class);
        $this->commands(Commands\ControllersCommand::class);
        $this->commands(Commands\AdminCommand::class);
    }

    /**
     * Register the commands accessible from the App.
     */
    private function registerAppCommands()
    {
        $this->commands(Commands\MakeModelCommand::class);
    }
}
