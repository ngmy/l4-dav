<?php

declare(strict_types=1);

namespace Ngmy\L4Dav\Tests;

use Mockery;
use Ngmy\L4Dav\{
    L4DavFacade,
    L4DavServiceProvider,
};
use Orchestra\Testbench\TestCase as OrchestraTestCase;

abstract class TestCase extends OrchestraTestCase
{
    protected function tearDown(): void
    {
        Mockery::close();

        parent::tearDown();
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     * @return array<int, string>
     */
    protected function getPackageProviders($app): array
    {
        return [
            L4DavServiceProvider::class,
        ];
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     * @return array<string, string>
     */
    protected function getPackageAliases($app): array
    {
        return [
            'L4Dav' => L4DavFacade::class,
        ];
    }
}
