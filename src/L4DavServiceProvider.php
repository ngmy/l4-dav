<?php

declare(strict_types=1);

namespace Ngmy\L4Dav;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Ngmy\L4Dav\Library\Curl;
use Ngmy\L4Dav\Service\Http\CurlRequest;

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
            $config = $app->make('config');

            $curl = new Curl();

            $request = new CurlRequest($curl);

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
