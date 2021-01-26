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
    private $options = [];

    /**
     * @param WebDavClientParameters $parameters
     * @return void
     */
    public function __construct(WebDavClientParameters $parameters)
    {
        $this->configureOptions($parameters);
    }

    /**
     * @param WebDavClientParameters $parameters
     * @return void
     */
    public function configureOptions(WebDavClientParameters $parameters): void
    {
        if (!\is_null($parameters->getPort())) {
            $this->options[\CURLOPT_PORT] = $parameters->getPort();
        }
        if (!\is_null($parameters->getCredential())) {
            $this->options[\CURLOPT_USERPWD] = (string) $parameters->getCredential();
        }
        $this->options[\CURLOPT_HTTPAUTH] = \CURLAUTH_ANY;
    }

    public function sendRequest(RequestInterface $request): ResponseInterface
    {
        return (new Client(
            MessageFactoryDiscovery::find(),
            StreamFactoryDiscovery::find(),
            $this->options
        ))
            ->sendRequest($request);
    }
}
