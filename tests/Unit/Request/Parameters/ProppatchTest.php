<?php

declare(strict_types=1);

namespace Ngmy\WebDav\Tests\Unit\Request\Parameters;

use LogicException;
use Ngmy\WebDav\Request;
use Ngmy\WebDav\Tests\TestCase;

class ProppatchTest extends TestCase
{
    public function testViolateInvariant(): void
    {
        $this->expectException(LogicException::class);
        Request\Parameters\Proppatch::createBuilder()->build();
    }
}
