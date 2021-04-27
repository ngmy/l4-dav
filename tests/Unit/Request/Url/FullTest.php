<?php

declare(strict_types=1);

namespace Ngmy\WebDav\Tests\Unit\Request\Url;

use Exception;
use InvalidArgumentException;
use Ngmy\WebDav\Request\Url;
use Ngmy\WebDav\Tests\TestCase;

use function get_class;
use function is_null;

class FullTest extends TestCase
{
    /**
     * @return list<list<mixed>>
     */
    public function instantiateClassProvider(): array
    {
        return [
            ['http://example.com', null],
            ['http://example.com:', null],
            ['http://user:password@example.com:80', null],
            ['http://example.com/', null],
            ['http://example.com/path', null],
            ['http://example.com/path/', null],
            ['http://example.com?key=value', null],
            ['http://example.com#fragment', null],
            ['https://example.com'],
            ['ftp://example.com', null, new InvalidArgumentException()],
            ['http://', null, new InvalidArgumentException()],
            ['path', null, new InvalidArgumentException()],
            ['http://example.com', null],
            ['/path', Url::createBaseUrl('http://example.com')],
            ['/path', null, new InvalidArgumentException()],
        ];
    }

    /**
     * @param Exception $expected
     * @dataProvider instantiateClassProvider
     */
    public function testInstantiateClass(string $url, ?Url\Base $baseUrl = null, $expected = null): void
    {
        if (is_null($expected)) {
            $this->expectNotToPerformAssertions();
        }
        if ($expected instanceof Exception) {
            $this->expectException(get_class($expected));
        }
        Url::createRequestUrl($url, $baseUrl);
    }
}
