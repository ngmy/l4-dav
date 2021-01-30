<?php

declare(strict_types=1);

namespace Ngmy\L4Dav;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class L4DavServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * Bootstrap the application events.
     */
    public function boot(): void
    {
        $configPath = __DIR__ . '/../config/ngmy-l4-dav.php';
        $this->mergeConfigFrom($configPath, 'ngmy-l4-dav');
        $this->publishes([$configPath => \config_path('ngmy-l4-dav.php')], 'ngmy-l4-dav');
    }

    /**
     * Register the service provider.
     */
    public function register(): void
    {
        $this->app->singleton(WebDavClient::class, function (Application $app) {
            $baseUrl  = $app->make('config')->get('ngmy-l4-dav.base_url');
            $port     = $app->make('config')->get('ngmy-l4-dav.port');
            $userName = $app->make('config')->get('ngmy-l4-dav.username');
            $password = $app->make('config')->get('ngmy-l4-dav.password');

            $optionsBuilder = new WebDavClientOptionsBuilder();
            if (!empty($baseUrl)) {
                $optionsBuilder->baseUrl($baseUrl);
            }
            if (!empty($port)) {
                $optionsBuilder->port($port);
            }
            if (!empty($userName)) {
                $optionsBuilder
                    ->userName($userName)
                    ->password($password);
            }
            $options = $optionsBuilder->build();
            return new WebDavClient($options);
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return list<string>
     */
    public function provides(): array
    {
        return [WebDavClient::class];
    }
}
