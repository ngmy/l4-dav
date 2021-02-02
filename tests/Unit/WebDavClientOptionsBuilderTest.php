<?php

declare(strict_types=1);

namespace Ngmy\L4Dav\Tests\Unit;

use Http\Discovery\Psr17FactoryDiscovery;
use League\Uri\Components\Port;
use League\Uri\Components\UserInfo;
use Ngmy\L4Dav\Headers;
use Ngmy\L4Dav\Tests\TestCase;
use Ngmy\L4Dav\Url;
use Ngmy\L4Dav\WebDavClientOptions;
use Ngmy\L4Dav\WebDavClientOptionsBuilder;

class WebDavClientOptionsBuilderTest extends TestCase
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
                new WebDavClientOptions(
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
                new WebDavClientOptions(
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
                new WebDavClientOptions(
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
                new WebDavClientOptions(
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
                new WebDavClientOptions(
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
                new WebDavClientOptions(
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
                ['curl_option_key' => 'curl_option_value'],
                new WebDavClientOptions(
                    null,
                    new Port(null),
                    new UserInfo(null, null),
                    new Headers(),
                    ['curl_option_key' => 'curl_option_value'],
                ),
            ],
            [
                'http://example.com',
                80,
                'username',
                'password',
                ['header_key' => 'header_value'],
                ['curl_option_key' => 'curl_option_value'],
                new WebDavClientOptions(
                    Url::createBaseUrl('http://example.com'),
                    new Port(80),
                    new UserInfo('username', 'password'),
                    new Headers(['header_key' => 'header_value']),
                    ['curl_option_key' => 'curl_option_value'],
                ),
            ],
        ];
    }

    /**
     * @param string|UriInterface|null $baseUrl
     * @param WebDavClientOptions      $expected
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
        $builder = new WebDavClientOptionsBuilder();
        if (!\is_null($baseUrl)) {
            $builder->baseUrl($baseUrl);
        }
        if (!\is_null($port)) {
            $builder->port($port);
        }
        if (!\is_null($userName)) {
            $builder->userName($userName);
        }
        if (!\is_null($password)) {
            $builder->password($password);
        }
        if (!\is_null($defaultRequestHeaders)) {
            $builder->defaultRequestHeaders($defaultRequestHeaders);
        }
        if (!\is_null($defaultCurlOptions)) {
            $builder->defaultCurlOptions($defaultCurlOptions);
        }
        $actual = $builder->build();
        $this->assertEquals($expected, $actual);
    }
}
