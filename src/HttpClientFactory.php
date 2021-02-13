<?php

declare(strict_types=1);

namespace Ngmy\PhpWebDav;

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

    public function create(): HttpClient
    {
        return new Client(
            // TODO: When cURL client supports PSR-17, use Psr17FactoryDiscovery instead
            MessageFactoryDiscovery::find(),
            // TODO: When cURL client supports PSR-17, use Psr17FactoryDiscovery instead
            StreamFactoryDiscovery::find(),
            $this->configureCurlOptions()
        );
    }

    /**
     * @return array<int, mixed>
     */
    private function configureCurlOptions(): array
    {
        $curlOptions = $this->options->getDefaultCurlOptions();
        $curlOptions = $this->options->getPort()->provide($curlOptions);
        $curlOptions = $this->options->getUserInfo()->provide($curlOptions);
        $curlOptions = $this->options->getAuthType()->provide($curlOptions);
        return $curlOptions;
    }
}
