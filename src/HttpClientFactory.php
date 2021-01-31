<?php

declare(strict_types=1);

namespace Ngmy\L4Dav;

use Http\Client\Curl\Client;
use Http\Client\HttpClient;
use Http\Discovery\MessageFactoryDiscovery;
use Http\Discovery\StreamFactoryDiscovery;

class HttpClientFactory
{
    /** @var WebDavClientOptions */
    private $options;

    public function __construct(WebDavClientOptions $options)
    {
        $this->options = $options;
    }

    /**
     * @param array<int, mixed> $curlOptions
     */
    public function create(array $curlOptions = []): HttpClient
    {
        return new Client(
            // TODO: When cURL client supports PSR-17, use Psr17FactoryDiscovery instead
            MessageFactoryDiscovery::find(),
            // TODO: When cURL client supports PSR-17, use Psr17FactoryDiscovery instead
            StreamFactoryDiscovery::find(),
            $this->configureCurlOptions($curlOptions)
        );
    }

    /**
     * @param array<int, mixed> $curlOptions
     * @return array<int, mixed>
     */
    private function configureCurlOptions(array $curlOptions): array
    {
        $curlOptions = $curlOptions + $this->options->defaultCurlOptions();
        if (!\is_null($this->options->port()->toInt())) {
            $curlOptions[\CURLOPT_PORT] = $this->options->port()->toInt();
        }
        if (!empty((string) $this->options->userInfo())) {
            $curlOptions[\CURLOPT_USERPWD] = (string) $this->options->userInfo();
        }
        $curlOptions[\CURLOPT_HTTPAUTH] = \CURLAUTH_ANY;
        return $curlOptions;
    }
}
