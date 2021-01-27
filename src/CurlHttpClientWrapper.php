<?php

declare(strict_types=1);

namespace Ngmy\L4Dav;

use Http\Client\Curl\Client;
use Http\Client\HttpClient;
use Http\Discovery\{
    MessageFactoryDiscovery,
    StreamFactoryDiscovery,
};
use Psr\Http\Message\{
    RequestInterface,
    ResponseInterface,
};

class CurlHttpClientWrapper implements HttpClient
{
    /** @var array<int, mixed> */
    private $curlOptions = [];

    /**
     * @param WebDavClientOptions $options
     * @return void
     */
    public function __construct(WebDavClientOptions $options)
    {
        $this->configureCurlOptions($options);
    }

    public function sendRequest(RequestInterface $request): ResponseInterface
    {
        return (new Client(
            MessageFactoryDiscovery::find(),
            StreamFactoryDiscovery::find(),
            $this->curlOptions
        ))
            ->sendRequest($request);
    }

    /**
     * @param WebDavClientOptions $options
     * @return void
     */
    private function configureCurlOptions(WebDavClientOptions $options): void
    {
        if (!\is_null($options->getPort())) {
            $this->curlOptions[\CURLOPT_PORT] = $options->getPort();
        }
        if (!\is_null($options->getCredential())) {
            $this->curlOptions[\CURLOPT_USERPWD] = (string) $options->getCredential();
        }
        $this->curlOptions[\CURLOPT_HTTPAUTH] = \CURLAUTH_ANY;
    }
}
