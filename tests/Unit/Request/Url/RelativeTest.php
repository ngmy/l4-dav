<?php

declare(strict_types=1);

namespace Ngmy\WebDav\Tests\Unit\Request\Url;

use Exception;
use InvalidArgumentException;
use Ngmy\WebDav\Request\Url;
use Ngmy\WebDav\Tests\TestCase;

use function get_class;
use function is_null;

class RelativeTest extends TestCase
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
        if (is_null($expected)) {
            $this->expectNotToPerformAssertions();
        }
        if ($expected instanceof Exception) {
            $this->expectException(get_class($expected));
        }
        Url::createRelativeUrl($url);
    }
}
