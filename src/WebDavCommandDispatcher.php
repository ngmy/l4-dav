<?php

declare(strict_types=1);

namespace Ngmy\PhpWebDav;

use GuzzleHttp\Psr7\Utils;
use Http\Client\HttpClient;
use Http\Discovery\Psr17FactoryDiscovery;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class WebDavCommandDispatcher
{
    /** @var WebDavCommand */
    private $command;
    /** @var HttpClient */
    private $httpClient;
    /** @var RequestInterface */
    private $request;

    public function __construct(WebDavCommand $command)
    {
        $this->command = $command;
        $this->httpClient = (new HttpClientFactory($command->getOptions()))->create();
        $request = Psr17FactoryDiscovery::findRequestFactory()
            ->createRequest($this->command->getMethod(), (string) $this->command->getUrl());
        $this->request = $this->configureRequest($request);
    }

    public function dispatch(): ResponseInterface
    {
        return $this->httpClient->sendRequest($this->request);
    }

    private function configureRequest(RequestInterface $request): RequestInterface
    {
        $newRequest = $request;
        $headers = $this->command->getOptions()->getDefaultRequestHeaders()
            ->withHeaders($this->command->getHeaders())
            ->toArray();
        foreach ($headers as $key => $value) {
            $newRequest = $newRequest->withHeader($key, $value);
        }
        return $this->command->getBody()
            ? $newRequest->withBody(Utils::streamFor($this->command->getBody()))
            : $newRequest;
    }
}
