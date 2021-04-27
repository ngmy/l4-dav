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

class CombinerTest extends TestCase
{
    /**
     * @return list<list<mixed>>
     */
    public function instantiateClassProvider(): array
    {
        return [
            [Url::createBaseUrl('http://example.com'), Url::createRelativeUrl('relative')],
            [Url::createBaseUrl('http://example.com'), Url::createRelativeUrl('/relative')],
            [Url::createBaseUrl('http://example.com/'), Url::createRelativeUrl('relative')],
            [Url::createBaseUrl('http://example.com/'), Url::createRelativeUrl('/relative')],
            [Url::createBaseUrl('http://example.com/path'), Url::createRelativeUrl('relative')],
            [Url::createBaseUrl('http://example.com/path'), Url::createRelativeUrl('/relative')],
            [Url::createBaseUrl('http://example.com/path/'), Url::createRelativeUrl('relative')],
            [Url::createBaseUrl('http://example.com/path/'), Url::createRelativeUrl('/relative')],
            [Url::createBaseUrl('http://example.com'), Url::createRelativeUrl('')],
            [Url::createBaseUrl('http://example.com/'), Url::createRelativeUrl('')],
            [Url::createBaseUrl('http://example.com/'), Url::createRelativeUrl('relative?key=value#fragment')],
        ];
    }

    /**
     * @param Exception|UriInterface $expected
     * @dataProvider instantiateClassProvider
     */
    public function testInstantiateClass(Url\Base $baseUrl, Url\Relative $relativeUrl, $expected = null): void
    {
        if (is_null($expected)) {
            $this->expectNotToPerformAssertions();
        }
        if ($expected instanceof Exception) {
            $this->expectException(get_class($expected));
        }
        new Url\Combiner($baseUrl, $relativeUrl);
    }

    /**
     * @return list<list<mixed>>
     */
    public function combineProvider(): array
    {
        return [
            [
                new Url\Combiner(
                    Url::createBaseUrl('http://example.com'),
                    Url::createRelativeUrl('relative')
                ),
                Url::createFullUrl(
                    Psr17FactoryDiscovery::findUriFactory()->createUri('http://example.com/relative')
                ),
            ],
            [
                new Url\Combiner(
                    Url::createBaseUrl('http://example.com'),
                    Url::createRelativeUrl('/relative')
                ),
                Url::createFullUrl(
                    Psr17FactoryDiscovery::findUriFactory()->createUri('http://example.com/relative')
                ),
            ],
            [
                new Url\Combiner(
                    Url::createBaseUrl('http://example.com/'),
                    Url::createRelativeUrl('relative')
                ),
                Url::createFullUrl(
                    Psr17FactoryDiscovery::findUriFactory()->createUri('http://example.com/relative')
                ),
            ],
            [
                new Url\Combiner(
                    Url::createBaseUrl('http://example.com/'),
                    Url::createRelativeUrl('/relative')
                ),
                Url::createFullUrl(
                    Psr17FactoryDiscovery::findUriFactory()->createUri('http://example.com/relative')
                ),
            ],
            [
                new Url\Combiner(
                    Url::createBaseUrl('http://example.com/path'),
                    Url::createRelativeUrl('relative')
                ),
                Url::createFullUrl(
                    Psr17FactoryDiscovery::findUriFactory()->createUri('http://example.com/path/relative')
                ),
            ],
            [
                new Url\Combiner(
                    Url::createBaseUrl('http://example.com/path'),
                    Url::createRelativeUrl('/relative')
                ),
                Url::createFullUrl(
                    Psr17FactoryDiscovery::findUriFactory()->createUri('http://example.com/path/relative')
                ),
            ],
            [
                new Url\Combiner(
                    Url::createBaseUrl('http://example.com/path/'),
                    Url::createRelativeUrl('relative')
                ),
                Url::createFullUrl(
                    Psr17FactoryDiscovery::findUriFactory()->createUri('http://example.com/path/relative')
                ),
            ],
            [
                new Url\Combiner(
                    Url::createBaseUrl('http://example.com/path/'),
                    Url::createRelativeUrl('/relative')
                ),
                Url::createFullUrl(
                    Psr17FactoryDiscovery::findUriFactory()->createUri('http://example.com/path/relative')
                ),
            ],
            [
                new Url\Combiner(
                    Url::createBaseUrl('http://example.com'),
                    Url::createRelativeUrl('')
                ),
                Url::createFullUrl(
                    Psr17FactoryDiscovery::findUriFactory()->createUri('http://example.com')
                ),
            ],
            [
                new Url\Combiner(
                    Url::createBaseUrl('http://example.com/'),
                    Url::createRelativeUrl('')
                ),
                Url::createFullUrl(
                    Psr17FactoryDiscovery::findUriFactory()->createUri('http://example.com/')
                ),
            ],
            [
                new Url\Combiner(
                    Url::createBaseUrl('http://example.com/'),
                    Url::createRelativeUrl('relative?key=value#fragment')
                ),
                Url::createFullUrl(
                    Psr17FactoryDiscovery::findUriFactory()->createUri('http://example.com/relative?key=value#fragment')
                ),
            ],
        ];
    }

    /**
     * @param Exception|UriInterface $expected
     * @dataProvider combineProvider
     */
    public function testCombine(Url\Combiner $urlCombiner, $expected): void
    {
        if ($expected instanceof Exception) {
            $this->expectException(get_class($expected));
        }
        $actual = $urlCombiner->combine();
        $this->assertEquals($expected, $actual);
    }
}
