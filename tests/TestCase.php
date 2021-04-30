<?php

declare(strict_types=1);

namespace Ngmy\WebDav\Tests;

use Mockery;
use PHPUnit\Framework\TestCase as PhpUnitTestCase;

use function file_exists;
use function sys_get_temp_dir;
use function uniqid;

use const DIRECTORY_SEPARATOR;

abstract class TestCase extends PhpUnitTestCase
{
    protected function tearDown(): void
    {
        Mockery::close();

        parent::tearDown();
    }

    protected function getUniqueTemporaryFilePath(): string
    {
        while (true) {
            $name = uniqid('php-webdav-client-', true);
            $path = sys_get_temp_dir() . DIRECTORY_SEPARATOR . $name;
            if (!file_exists($path)) {
                return $path;
            }
        }
    }
}
