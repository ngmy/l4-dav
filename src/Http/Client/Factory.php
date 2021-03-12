<?php

declare(strict_types=1);

namespace Ngmy\WebDav\Http\Client;

use Http\Client\Curl;
use Http\Client\HttpClient;
use Http\Discovery\Psr17FactoryDiscovery;
use Ngmy\WebDav\Client;

class Factory
{
    /** @var Client\Options */
    private $options;

    public function __construct(Client\Options $options)
    {
        $this->options = $options;
    }

    public function create(): HttpClient
    {
        return new Curl\Client(
            Psr17FactoryDiscovery::findResponseFactory(),
            Psr17FactoryDiscovery::findStreamFactory(),
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
