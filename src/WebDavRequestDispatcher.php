<?php

declare(strict_types=1);

namespace Ngmy\PhpWebDav;

use Http\Client\HttpClient;
use Http\Discovery\Psr17FactoryDiscovery;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class WebDavRequestDispatcher
{
    /** @var HttpClient */
    private $httpClient;

    public function __construct(HttpClient $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    public function dispatch(WebDavRequestCommand $command): ResponseInterface
    {
        $request = Psr17FactoryDiscovery::findRequestFactory()
            ->createRequest((string) $command->getMethod(), (string) $command->getUrl());
        $request = $this->configureRequest($request, $command);
        return $this->httpClient->sendRequest($request);
    }

    private function configureRequest(RequestInterface $request, WebDavRequestCommand $command): RequestInterface
    {
        $newRequest = $request;
        $newRequest = $command->getHeaders()->provide($newRequest);
        $newRequest = $command->getBody()->provide($newRequest);
        return $newRequest;
    }
}
