<?php

declare(strict_types=1);

namespace Ngmy\WebDav\Tests\Unit\Request\Url;

use Exception;
use Http\Discovery\Psr17FactoryDiscovery;
use InvalidArgumentException;
use Ngmy\WebDav\Request\Url;
use Ngmy\WebDav\Tests\TestCase;
use Psr\Http\Message\UriInterface;

use function get_class;
use function is_null;

class BaseTest extends TestCase
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
            ['http://example.com?key=value', new InvalidArgumentException()],
            ['http://example.com#fragment', new InvalidArgumentException()],
            ['https://example.com'],
            ['ftp://example.com', new InvalidArgumentException()],
            ['http://', new InvalidArgumentException()],
            ['path', new InvalidArgumentException()],
            [(function () {
                return Psr17FactoryDiscovery::findUriFactory()
                    ->createUri()
                    ->withScheme('http')
                    ;
            })(), new InvalidArgumentException()],
        ];
    }

    /**
     * @param string|UriInterface $url
     * @param Exception           $expected
     * @dataProvider instantiateClassProvider
     */
    public function testInstantiateClass($url, $expected = null): void
    {
        if (is_null($expected)) {
            $this->expectNotToPerformAssertions();
        }
        if ($expected instanceof Exception) {
            $this->expectException(get_class($expected));
        }
        Url::createBaseUrl($url);
    }

    /**
     * @return list<list<mixed>>
     */
    public function createFullUrlWithRelativeUrlProvider(): array
    {
        return [
            [
                Url::createBaseUrl('http://example.com'),
                Url::createRelativeUrl('relative'),
                Url::createFullUrl(
                    Psr17FactoryDiscovery::findUriFactory()->createUri('http://example.com/relative')
                ),
            ],
            [
                Url::createBaseUrl('http://example.com'),
                Url::createRelativeUrl('/relative'),
                Url::createFullUrl(
                    Psr17FactoryDiscovery::findUriFactory()->createUri('http://example.com/relative')
                ),
            ],
            [
                Url::createBaseUrl('http://example.com/'),
                Url::createRelativeUrl('relative'),
                Url::createFullUrl(
                    Psr17FactoryDiscovery::findUriFactory()->createUri('http://example.com/relative')
                ),
            ],
            [
                Url::createBaseUrl('http://example.com/'),
                Url::createRelativeUrl('/relative'),
                Url::createFullUrl(
                    Psr17FactoryDiscovery::findUriFactory()->createUri('http://example.com/relative')
                ),
            ],
            [
                Url::createBaseUrl('http://example.com/path'),
                Url::createRelativeUrl('relative'),
                Url::createFullUrl(
                    Psr17FactoryDiscovery::findUriFactory()->createUri('http://example.com/path/relative')
                ),
            ],
            [
                Url::createBaseUrl('http://example.com/path'),
                Url::createRelativeUrl('/relative'),
                Url::createFullUrl(
                    Psr17FactoryDiscovery::findUriFactory()->createUri('http://example.com/path/relative')
                ),
            ],
            [
                Url::createBaseUrl('http://example.com/path/'),
                Url::createRelativeUrl('relative'),
                Url::createFullUrl(
                    Psr17FactoryDiscovery::findUriFactory()->createUri('http://example.com/path/relative')
                ),
            ],
            [
                Url::createBaseUrl('http://example.com/path/'),
                Url::createRelativeUrl('/relative'),
                Url::createFullUrl(
                    Psr17FactoryDiscovery::findUriFactory()->createUri('http://example.com/path/relative')
                ),
            ],
            [
                Url::createBaseUrl('http://example.com'),
                Url::createRelativeUrl(''),
                Url::createFullUrl(
                    Psr17FactoryDiscovery::findUriFactory()->createUri('http://example.com')
                ),
            ],
            [
                Url::createBaseUrl('http://example.com/'),
                Url::createRelativeUrl(''),
                Url::createFullUrl(
                    Psr17FactoryDiscovery::findUriFactory()->createUri('http://example.com/')
                ),
            ],
            [
                Url::createBaseUrl('http://example.com/'),
                Url::createRelativeUrl('relative?key=value#fragment'),
                Url::createFullUrl(
                    Psr17FactoryDiscovery::findUriFactory()->createUri('http://example.com/relative?key=value#fragment')
                ),
            ],
        ];
    }

    /**
     * @param Exception|UriInterface $expected
     * @dataProvider createFullUrlWithRelativeUrlProvider
     */
    public function testCreateFullUrlWithRelativeUrl(Url\Base $baseUrl, Url\Relative $relativeUrl, $expected): void
    {
        if ($expected instanceof Exception) {
            $this->expectException(get_class($expected));
        }
        $actual = $baseUrl->createFullUrlWithRelativeUrl($relativeUrl);
        $this->assertEquals($expected, $actual);
    }
}
