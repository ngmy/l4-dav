<?php

declare(strict_types=1);

namespace Ngmy\L4Dav;

use anlutro\cURL\cURL as Curl;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class L4DavServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot(): void
    {
        $configPath = __DIR__ . '/../config/ngmy-l4-dav.php';
        $this->mergeConfigFrom($configPath, 'ngmy-l4-dav');
        $this->publishes([$configPath => \config_path('ngmy-l4-dav.php')], 'ngmy-l4-dav');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->singleton(L4Dav::class, function (Application $app) {
            $request = new Request(new Curl());

            $config = $app->make('config');
            $webDavUrl  = $config->get('ngmy-l4-dav.url');
            $webDavPort = $config->get('ngmy-l4-dav.port');

            return new L4Dav($request, $webDavUrl, $webDavPort);
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return list<string>
     */
    public function provides(): array
    {
        return [L4Dav::class];
    }
}
