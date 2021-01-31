<?php

declare(strict_types=1);

namespace Ngmy\L4Dav\Tests\Unit;

use Exception;
use InvalidArgumentException;
use Ngmy\L4Dav\BaseUrl;
use Ngmy\L4Dav\FullUrl;
use Ngmy\L4Dav\Tests\TestCase;

class FullUrlTest extends TestCase
{
    /**
     * @return list<list<mixed>>
     */
    public function instantiateClassProvider(): array
    {
        return [
            ['http://example.com'],
            ['http://example.com:'],
            ['http://user:password@example.com:80'],
            ['http://example.com/'],
            ['http://example.com/path'],
            ['http://example.com/path/'],
            ['http://example.com?key=value'],
            ['http://example.com#fragment'],
            ['https://example.com'],
            ['ftp://example.com', new InvalidArgumentException()],
            ['http://', new InvalidArgumentException()],
            ['path', new InvalidArgumentException()],
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
        new FullUrl($url);
    }

    /**
     * @return list<list<mixed>>
     */
    public function createFromBaseUrlProvider(): array
    {
        return [
            ['http://example.com', null, new FullUrl('http://example.com')],
            ['/path', new BaseUrl('http://example.com'), new FullUrl('http://example.com/path')],
            ['/path', null, new InvalidArgumentException()],
        ];
    }

    /**
     * @param Exception|FullUrl $expected
     * @dataProvider createFromBaseUrlProvider
     */
    public function testCreateFromBaseUrl(string $url, ?BaseUrl $baseUrl = null, $expected): void
    {
        if ($expected instanceof Exception) {
            $this->expectException(\get_class($expected));
        }
        $actual = FullUrl::createFromBaseUrl($url, $baseUrl);
        $this->assertEquals($expected, $actual);
    }
}
