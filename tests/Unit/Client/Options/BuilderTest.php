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
                new Client\Options(
                    null,
                    new Request\Headers()
                ),
            ],
            [
                'http://example.com',
                null,
                new Client\Options(
                    Request\Url::createBaseUrl('http://example.com'),
                    new Request\Headers()
                ),
            ],
            [
                Psr17FactoryDiscovery::findUriFactory()->createUri('http://example.com'),
                null,
                new Client\Options(
                    Request\Url::createBaseUrl('http://example.com'),
                    new Request\Headers()
                ),
            ],
            [
                null,
                null,
                new Client\Options(
                    null,
                    new Request\Headers()
                ),
            ],
            [
                null,
                null,
                new Client\Options(
                    null,
                    new Request\Headers()
                ),
            ],
            [
                null,
                ['header_key' => 'header_value'],
                new Client\Options(
                    null,
                    new Request\Headers(['header_key' => 'header_value'])
                ),
            ],
            [
                null,
                null,
                new Client\Options(
                    null,
                    new Request\Headers()
                ),
            ],
            [
                'http://example.com',
                ['header_key' => 'header_value'],
                new Client\Options(
                    Request\Url::createBaseUrl('http://example.com'),
                    new Request\Headers(['header_key' => 'header_value'])
                ),
            ],
        ];
    }

    /**
     * @param string|UriInterface|null   $baseUrl
     * @param array<string, string>|null $defaultRequestHeaders
     * @param Client\Options             $expected
     * @dataProvider buildProvider
     */
    public function testBuild(
        $baseUrl,
        ?array $defaultRequestHeaders,
        $expected
    ): void {
        $builder = new Client\Options\Builder();
        if (!is_null($baseUrl)) {
            $builder->setBaseUrl($baseUrl);
        }
        if (!is_null($defaultRequestHeaders)) {
            $builder->setDefaultRequestHeaders($defaultRequestHeaders);
        }
        $actual = $builder->build();
        $this->assertEquals($expected, $actual);
    }
}
