<?php

declare(strict_types=1);

namespace Ngmy\PhpWebDav;

use GuzzleHttp\Psr7\Utils;
use Http\Client\HttpClient;
use Http\Discovery\Psr17FactoryDiscovery;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class WebDavCommandDispatcher
{
    /** @var WebDavClientOptions */
    private $options;
    /** @var HttpClient */
    private $httpClient;

    public function __construct(WebDavClientOptions $options, HttpClient $httpClient)
    {
        $this->options = $options;
        $this->httpClient = $httpClient;
    }

    /**
     * @param resource|StreamInterface|string|null $body
     */
    public function dispatch(
        WebDavMethod $method,
        FullUrl $url,
        ?Headers $headers = null,
        $body = null
    ): ResponseInterface {
        $request = Psr17FactoryDiscovery::findRequestFactory()
            ->createRequest((string) $method, (string) $url);
        $request = $this->configureRequest($request, $headers, $body);
        return $this->httpClient->sendRequest($request);
    }

    /**
     * @param resource|StreamInterface|string|null $body
     */
    private function configureRequest(RequestInterface $request, ?Headers $headers, $body): RequestInterface
    {
        $newRequest = $request;
        $headers = $headers ?: new Headers();
        $headers = $this->options->getDefaultRequestHeaders()
            ->withHeaders($headers)
            ->toArray();
        foreach ($headers as $key => $value) {
            $newRequest = $newRequest->withHeader($key, $value);
        }
        return $body
            ? $newRequest->withBody(Utils::streamFor($body))
            : $newRequest;
    }
}
