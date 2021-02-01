<?php

declare(strict_types=1);

namespace Ngmy\L4Dav;

use GuzzleHttp\Psr7\Utils;
use Http\Client\HttpClient;
use Http\Discovery\Psr17FactoryDiscovery;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;

abstract class Command
{
    /** @var WebDavClientOptions */
    private $options;
    /** @var string */
    private $method;
    /** @var FullUrl */
    private $uri;
    /** @var Headers */
    private $headers;
    /** @var resource|StreamInterface|string|null */
    private $body;
    /** @var HttpClient */
    private $httpClient;
    /** @var ResponseInterface */
    private $response;

    /**
     * @param string|UriInterface                  $uri
     * @param Headers                              $headers
     * @param resource|StreamInterface|string|null $body
     */
    public function __construct(
        WebDavClientOptions $options,
        string $method,
        $uri,
        Headers $headers = null,
        $body = null
    ) {
        $this->options = $options;
        $this->method = $method;
        $this->uri = Url::createFullUrl($uri, $options->baseUrl());
        $this->headers = $headers ?: new Headers([]);
        $this->body = $body;
        $this->httpClient = (new HttpClientFactory($options))->create();
    }

    public function execute(): ResponseInterface
    {
        $this->preRequest();
        $this->sendRequest();
        $this->postRequest();
        return $this->getResponse();
    }

    protected function preRequest(): void
    {
    }

    protected function postRequest(): void
    {
    }

    protected function sendRequest(): void
    {
        $request = Psr17FactoryDiscovery::findRequestFactory()->createRequest($this->method, (string) $this->uri);
        $headers = $this->options->defaultRequestHeaders()
            ->withHeaders($this->headers)
            ->toArray();
        foreach ($headers as $key => $value) {
            $request = $request->withHeader($key, $value);
        }
        $request = $this->body ? $request->withBody(Utils::streamFor($this->body)) : $request;
        $this->response = $this->httpClient->sendRequest($request);
    }

    protected function getOptions(): WebDavClientOptions
    {
        return $this->options;
    }

    protected function getMethod(): string
    {
        return $this->method;
    }

    protected function getUri(): FullUrl
    {
        return $this->uri;
    }

    protected function getHeaders(): Headers
    {
        return $this->headers;
    }

    /**
     * @return resource|StreamInterface|string|null
     */
    protected function getBody()
    {
        return $this->body;
    }

    protected function getResponse(): ResponseInterface
    {
        return $this->response;
    }

    protected function getHttpClient(): HttpClient
    {
        return $this->httpClient;
    }
}
