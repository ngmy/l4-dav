<?php

declare(strict_types=1);

namespace Ngmy\WebDav\Tests\Unit;

use Exception;
use Http\Discovery\Psr17FactoryDiscovery;
use InvalidArgumentException;
use Ngmy\WebDav\BaseUrl;
use Ngmy\WebDav\Tests\TestCase;
use Ngmy\WebDav\Url;
use Ngmy\WebDav\UrlCombiner;
use Psr\Http\Message\UriInterface;

use function get_class;
use function is_null;

class UrlCombinerTest extends TestCase
{
    /**
     * @return list<list<mixed>>
     */
    public function instantiateClassProvider(): array
    {
        return [
            [Url::createBaseUrl('http://example.com'), 'shortcut'],
            [Url::createBaseUrl('http://example.com'), '/shortcut'],
            [Url::createBaseUrl('http://example.com/'), 'shortcut'],
            [Url::createBaseUrl('http://example.com/'), '/shortcut'],
            [Url::createBaseUrl('http://example.com/path'), 'shortcut'],
            [Url::createBaseUrl('http://example.com/path'), '/shortcut'],
            [Url::createBaseUrl('http://example.com/path/'), 'shortcut'],
            [Url::createBaseUrl('http://example.com/path/'), '/shortcut'],
            [Url::createBaseUrl('http://example.com'), ''],
            [Url::createBaseUrl('http://example.com/'), ''],
            [Url::createBaseUrl('http://example.com/'), 'shortcut?key=value#fragment'],
            [Url::createBaseUrl('http://example.com/'), 'http://example.com/', new InvalidArgumentException()],
        ];
    }

    /**
     * @param Exception|UriInterface $expected
     * @dataProvider instantiateClassProvider
     */
    public function testInstantiateClass(BaseUrl $baseUrl, string $shortcutUrl, $expected = null): void
    {
        if (is_null($expected)) {
            $this->expectNotToPerformAssertions();
        }
        if ($expected instanceof Exception) {
            $this->expectException(get_class($expected));
        }
        new UrlCombiner($baseUrl, $shortcutUrl);
    }

    /**
     * @return list<list<mixed>>
     */
    public function combineProvider(): array
    {
        return [
            [
                new UrlCombiner(
                    Url::createBaseUrl('http://example.com'),
                    'shortcut'
                ),
                Psr17FactoryDiscovery::findUriFactory()->createUri('http://example.com/shortcut'),
            ],
            [
                new UrlCombiner(
                    Url::createBaseUrl('http://example.com'),
                    '/shortcut'
                ),
                Psr17FactoryDiscovery::findUriFactory()->createUri('http://example.com/shortcut'),
            ],
            [
                new UrlCombiner(
                    Url::createBaseUrl('http://example.com/'),
                    'shortcut'
                ),
                Psr17FactoryDiscovery::findUriFactory()->createUri('http://example.com/shortcut'),
            ],
            [
                new UrlCombiner(
                    Url::createBaseUrl('http://example.com/'),
                    '/shortcut'
                ),
                Psr17FactoryDiscovery::findUriFactory()->createUri('http://example.com/shortcut'),
            ],
            [
                new UrlCombiner(
                    Url::createBaseUrl('http://example.com/path'),
                    'shortcut'
                ),
                Psr17FactoryDiscovery::findUriFactory()->createUri('http://example.com/path/shortcut'),
            ],
            [
                new UrlCombiner(
                    Url::createBaseUrl('http://example.com/path'),
                    '/shortcut'
                ),
                Psr17FactoryDiscovery::findUriFactory()->createUri('http://example.com/path/shortcut'),
            ],
            [
                new UrlCombiner(
                    Url::createBaseUrl('http://example.com/path/'),
                    'shortcut'
                ),
                Psr17FactoryDiscovery::findUriFactory()->createUri('http://example.com/path/shortcut'),
            ],
            [
                new UrlCombiner(
                    Url::createBaseUrl('http://example.com/path/'),
                    '/shortcut'
                ),
                Psr17FactoryDiscovery::findUriFactory()->createUri('http://example.com/path/shortcut'),
            ],
            [
                new UrlCombiner(
                    Url::createBaseUrl('http://example.com'),
                    ''
                ),
                Psr17FactoryDiscovery::findUriFactory()->createUri('http://example.com'),
            ],
            [
                new UrlCombiner(
                    Url::createBaseUrl('http://example.com/'),
                    ''
                ),
                Psr17FactoryDiscovery::findUriFactory()->createUri('http://example.com/'),
            ],
            [
                new UrlCombiner(
                    Url::createBaseUrl('http://example.com/'),
                    'shortcut?key=value#fragment'
                ),
                Psr17FactoryDiscovery::findUriFactory()->createUri('http://example.com/shortcut?key=value#fragment'),
            ],
        ];
    }

    /**
     * @param Exception|UriInterface $expected
     * @dataProvider combineProvider
     */
    public function testCombine(UrlCombiner $urlCombiner, $expected): void
    {
        if ($expected instanceof Exception) {
            $this->expectException(get_class($expected));
        }
        $actual = $urlCombiner->combine();
        $this->assertEquals($expected, $actual);
    }
}
