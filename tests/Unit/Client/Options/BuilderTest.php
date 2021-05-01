<?php

declare(strict_types=1);

namespace Ngmy\WebDav\Tests\Unit\Client\Options;

use Http\Discovery\Psr17FactoryDiscovery;
use Ngmy\WebDav\Client;
use Ngmy\WebDav\Request;
use Ngmy\WebDav\Tests\TestCase;
use Psr\Http\Message\UriInterface;

use function is_null;

class BuilderTest extends TestCase
{
    /**
     * @return list<list<mixed>>
     */
    public function buildProvider(): array
    {
        return [
            [
                null,
                null,
            ],
            [
                'http://example.com',
                null,
            ],
            [
                Psr17FactoryDiscovery::findUriFactory()->createUri('http://example.com'),
                null,
            ],
            [
                null,
                null,
            ],
            [
                null,
                null,
            ],
            [
                null,
                ['header_key' => 'header_value'],
            ],
            [
                null,
                null,
            ],
            [
                'http://example.com',
                ['header_key' => 'header_value'],
            ],
        ];
    }

    /**
     * @param string|UriInterface|null   $baseUrl
     * @param array<string, string>|null $defaultRequestHeaders
     * @dataProvider buildProvider
     */
    public function testBuild(
        $baseUrl,
        ?array $defaultRequestHeaders
    ): void {
        $builder = Client\Options::createBuilder();
        if (!is_null($baseUrl)) {
            $builder = $builder->setBaseUrl($baseUrl);
        }
        if (!is_null($defaultRequestHeaders)) {
            $builder = $builder->setDefaultRequestHeaders($defaultRequestHeaders);
        }
        $actual = $builder->build();
        $this->assertEquals(
            is_null($baseUrl) ? null : Request\Url::createBaseUrl($baseUrl),
            $actual->getBaseUrl()
        );
        $this->assertEquals(
            is_null($defaultRequestHeaders) ? new Request\Headers() : new Request\Headers($defaultRequestHeaders),
            $actual->getDefaultRequestHeaders()
        );
    }
}
