<?php

declare(strict_types=1);

namespace Ngmy\L4Dav\Tests\Unit;

use Exception;
use InvalidArgumentException;
use Ngmy\L4Dav\Url;
use Ngmy\L4Dav\Tests\TestCase;

class ShortcutUrlTest extends TestCase
{
    /**
     * @return list<list<mixed>>
     */
    public function instantiateClassProvider(): array
    {
        return [
            ['/'],
            [''],
            ['/path'],
            ['/path/'],
            ['path'],
            ['path/'],
            ['/?key=value'],
            ['/#fragment'],
            ['http://example.com', new InvalidArgumentException()],
            ['//user:password@example.com:80', new InvalidArgumentException()],
        ];
    }

    /**
     * @param Exception $expected
     * @dataProvider instantiateClassProvider
     */
    public function testInstantiateClass(string $url, $expected = null): void
    {
        if (\is_null($expected)) {
            $this->expectNotToPerformAssertions();
        }
        if ($expected instanceof Exception) {
            $this->expectException(\get_class($expected));
        }
        Url::createShortcutUrl($url);
    }
}
