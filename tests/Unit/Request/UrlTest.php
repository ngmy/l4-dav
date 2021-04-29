<?php

declare(strict_types=1);

namespace Ngmy\WebDav\Tests\Unit\Request;

use Exception;
use Http\Discovery\Psr17FactoryDiscovery;
use InvalidArgumentException;
use Ngmy\WebDav\Request;
use Ngmy\WebDav\Tests\TestCase;
use Psr\Http\Message\UriInterface;

use function assert;
use function get_class;

class UrlTest extends TestCase
{
    /**
     * @return list<list<mixed>>
     */
    public function createRequestUrlProvider(): array
    {
        return [
            [
                'http://example.com/path',
                null,
                Request\Url::createFullUrl('http://example.com/path'),
            ],
            [
                'path',
                null,
                new InvalidArgumentException(),
            ],
            [
                'path',
                Request\Url::createBaseUrl('http://example.com'),
                Request\Url::createFullUrl('http://example.com/path'),
            ],
            [
                'http://exampl.com/path',
                Request\Url::createBaseUrl('http://example.com'),
                new InvalidArgumentException(),
            ],
        ];
    }

    /**
     * @param string|UriInterface        $url
     * @param Exception|Request\Url\Full $expected
     * @dataProvider createRequestUrlProvider
     */
    public function testCreateRequestUrl($url, ?Request\Url\Base $baseUrl, $expected): void
    {
        if ($expected instanceof Exception) {
            $this->expectException(get_class($expected));
        }

        $actual = Request\Url::createRequestUrl($url, $baseUrl);

        assert($expected instanceof Request\Url\Full);
        $this->assertEquals($expected, $actual);
    }

    /**
     * @return list<list<mixed>>
     */
    public function createDestinationUrlProvider(): array
    {
        return [
            [
                'http://example.com/path',
                null,
                Request\Url::createFullUrl('http://example.com/path'),
            ],
            [
                'path',
                null,
                Request\Url::createRelativeUrl('path'),
            ],
            [
                (function () {
                    return Psr17FactoryDiscovery::findUriFactory()
                        ->createUri()
                        ->withScheme('http')
                        ;
                })(),
                null,
                new InvalidArgumentException(),
            ],
            [
                'path',
                Request\Url::createBaseUrl('http://example.com'),
                Request\Url::createFullUrl('http://example.com/path'),
            ],
            [
                'http://example.com/path',
                Request\Url::createBaseUrl('http://example.com'),
                new InvalidArgumentException(),
            ],
        ];
    }

    /**
     * @param string|UriInterface                             $url
     * @param Exception|Request\Url\Full|Request\Url\Relative $expected
     * @dataProvider createDestinationUrlProvider
     */
    public function testCreateDestinationUrl($url, ?Request\Url\Base $baseUrl, $expected): void
    {
        if ($expected instanceof Exception) {
            $this->expectException(get_class($expected));
        }

        $actual = Request\Url::createDestinationUrl($url, $baseUrl);

        assert($expected instanceof Request\Url\Full || $expected instanceof Request\Url\Relative);
        $this->assertEquals($expected, $actual);
    }
}
