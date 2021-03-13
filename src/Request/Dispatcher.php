<?php

declare(strict_types=1);

namespace Ngmy\WebDav\Request;

use Http\Discovery\Psr17FactoryDiscovery;
use Ngmy\WebDav\Request;
use Psr\Http\Client\ClientInterface as HttpClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class Dispatcher
{
    /** @var HttpClientInterface */
    private $httpClient;

    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    public function dispatch(Request\Command $command): ResponseInterface
    {
        $request = Psr17FactoryDiscovery::findRequestFactory()
            ->createRequest((string) $command->getMethod(), (string) $command->getUrl());
        $request = $this->configureRequest($request, $command);
        return $this->httpClient->sendRequest($request);
    }

    private function configureRequest(RequestInterface $request, Request\Command $command): RequestInterface
    {
        $newRequest = $request;
        $newRequest = $command->getHeaders()->provide($newRequest);
        $newRequest = $command->getBody()->provide($newRequest);
        return $newRequest;
    }
}
