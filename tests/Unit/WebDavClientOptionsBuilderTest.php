<?php

declare(strict_types=1);

namespace Ngmy\WebDav\Tests\Unit;

use Http\Discovery\Psr17FactoryDiscovery;
use League\Uri\Components\Port;
use League\Uri\Components\UserInfo;
use Ngmy\WebDav\ClientOptions;
use Ngmy\WebDav\ClientOptionsBuilder;
use Ngmy\WebDav\Headers;
use Ngmy\WebDav\Tests\TestCase;
use Ngmy\WebDav\Url;
use Psr\Http\Message\UriInterface;

use function is_null;

use const CURLOPT_PORT;

class ClientOptionsBuilderTest extends TestCase
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
                null,
                null,
                null,
                null,
                new ClientOptions(
                    null,
                    new Port(null),
                    new UserInfo(null, null),
                    new Headers(),
                    [],
                ),
            ],
            [
                'http://example.com',
                null,
                null,
                null,
                null,
                null,
                new ClientOptions(
                    Url::createBaseUrl('http://example.com'),
                    new Port(null),
                    new UserInfo(null, null),
                    new Headers(),
                    [],
                ),
            ],
            [
                Psr17FactoryDiscovery::findUriFactory()->createUri('http://example.com'),
                null,
                null,
                null,
                null,
                null,
                new ClientOptions(
                    Url::createBaseUrl('http://example.com'),
                    new Port(null),
                    new UserInfo(null, null),
                    new Headers(),
                    [],
                ),
            ],
            [
                null,
                80,
                null,
                null,
                null,
                null,
                new ClientOptions(
                    null,
                    new Port(80),
                    new UserInfo(null, null),
                    new Headers(),
                    [],
                ),
            ],
            [
                null,
                null,
                'username',
                'password',
                null,
                null,
                new ClientOptions(
                    null,
                    new Port(null),
                    new UserInfo('username', 'password'),
                    new Headers(),
                    [],
                ),
            ],
            [
                null,
                null,
                null,
                null,
                ['header_key' => 'header_value'],
                null,
                new ClientOptions(
                    null,
                    new Port(null),
                    new UserInfo(null, null),
                    new Headers(['header_key' => 'header_value']),
                    [],
                ),
            ],
            [
                null,
                null,
                null,
                null,
                null,
                [CURLOPT_PORT => 80],
                new ClientOptions(
                    null,
                    new Port(null),
                    new UserInfo(null, null),
                    new Headers(),
                    [CURLOPT_PORT => 80],
                ),
            ],
            [
                'http://example.com',
                80,
                'username',
                'password',
                ['header_key' => 'header_value'],
                [CURLOPT_PORT => 80],
                new ClientOptions(
                    Url::createBaseUrl('http://example.com'),
                    new Port(80),
                    new UserInfo('username', 'password'),
                    new Headers(['header_key' => 'header_value']),
                    [CURLOPT_PORT => 80],
                ),
            ],
        ];
    }

    /**
     * @param string|UriInterface|null   $baseUrl
     * @param array<string, string>|null $defaultRequestHeaders
     * @param list<mixed>|null           $defaultCurlOptions
     * @param ClientOptions              $expected
     * @dataProvider buildProvider
     */
    public function testBuild(
        $baseUrl,
        ?int $port,
        ?string $userName,
        ?string $password,
        ?array $defaultRequestHeaders,
        ?array $defaultCurlOptions,
        $expected
    ): void {
        $builder = new ClientOptionsBuilder();
        if (!is_null($baseUrl)) {
            $builder->baseUrl($baseUrl);
        }
        if (!is_null($port)) {
            $builder->port($port);
        }
        if (!is_null($userName)) {
            $builder->userName($userName);
        }
        if (!is_null($password)) {
            $builder->password($password);
        }
        if (!is_null($defaultRequestHeaders)) {
            $builder->defaultRequestHeaders($defaultRequestHeaders);
        }
        if (!is_null($defaultCurlOptions)) {
            $builder->defaultCurlOptions($defaultCurlOptions);
        }
        $actual = $builder->build();
        $this->assertEquals($expected, $actual);
    }
}
