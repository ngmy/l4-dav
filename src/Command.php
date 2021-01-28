<?php

declare(strict_types=1);

namespace Ngmy\L4Dav;

use GuzzleHttp\Psr7\{
    Request,
    Utils as Psr7Utils,
};
use Http\Client\HttpClient;
use Psr\Http\Message\{
    ResponseInterface,
    StreamInterface,
};

abstract class Command
{
    /** @var WebDavClientOptions */
    private $options;
    /** @var string */
    private $method;
    /** @var string */
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
     * @param WebDavClientOptions                  $options
     * @param string                               $method
     * @param string                               $uri
     * @param Headers                              $headers
     * @param resource|StreamInterface|string|null $body
     * @return void
     */
    public function __construct(
        WebDavClientOptions $options,
        string $method,
        string $uri,
        Headers $headers = null,
        $body = null
    ) {
        $this->options = $options;
        $this->method = $method;
        $this->uri = $uri;
        $this->headers = $headers ?? new Headers([]);
        $this->body = $body;
        $this->httpClient = HttpClientFactory::create($options);
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
        $request = new Request($this->method, Utils::resolveUri($this->uri, $this->options));
        $headers = $this->options->getDefaultRequestHeaders()
            ->addHeaders($this->headers)
            ->toArray();
        foreach ($headers as $key => $value) {
            $request = $request->withHeader($key, $value);
        }
        $request = $this->body ? $request->withBody(Psr7Utils::streamFor($this->body)) : $request;
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

    protected function getUri(): string
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
