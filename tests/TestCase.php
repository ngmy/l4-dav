<?php

declare(strict_types=1);

namespace Ngmy\PhpWebDav\Tests;

use Mockery;
use PHPUnit\Framework\TestCase as PhpUnitTestCase;

abstract class TestCase extends PhpUnitTestCase
{
    protected function tearDown(): void
    {
        Mockery::close();

        parent::tearDown();
    }
}
