<?php

declare(strict_types=1);

namespace Ngmy\PhpWebDav;

use Http\Discovery\Psr17FactoryDiscovery;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;
use RuntimeException;

class WebDavCommand
{
    /** @var string */
    private $method;
    /** @var FullUrl */
    private $url;
    /** @var CopyParameters|DeleteParameters|GetParameters|HeadParameters|MkcolParameters|MoveParameters|PropfindParameters|ProppatchParameters|PutParameters */
    private $parameters;
    /** @var WebDavClientOptions */
    private $options;
    /** @var Headers */
    private $headers;
    /** @var resource|StreamInterface|string|null */
    private $body;
    /** @var WebDavCommandDispatcher */
    private $dispatcher;
    /** @var ResponseInterface */
    private $response;

    /**
     * @param string|UriInterface $url
     */
    public static function createGetCommand(
        $url,
        GetParameters $parameters,
        WebDavClientOptions $options
    ): self {
        return new self('GET', $url, $parameters, $options);
    }

    /**
     * @param string|UriInterface $url
     * @throws RuntimeException
     */
    public static function createPutCommand(
        $url,
        PutParameters $parameters,
        WebDavClientOptions $options
    ): self {
        $fh = \fopen($parameters->getSourcePath(), 'r');
        if ($fh === false) {
            throw new RuntimeException(\sprintf('Failed to open the file "%s".', $parameters->getSourcePath()));
        }
        $body = Psr17FactoryDiscovery::findStreamFactory()->createStreamFromResource($fh);
        return new self('PUT', $url, $parameters, $options, new Headers([
            'Content-Length' => (string) \filesize($parameters->getSourcePath()),
        ]), $body);
    }

    /**
     * @param string|UriInterface $url
     */
    public static function createDeleteCommand(
        $url,
        DeleteParameters $parameters,
        WebDavClientOptions $options
    ): self {
        return new self('DELETE', $url, $parameters, $options);
    }

    /**
     * @param string|UriInterface $url
     */
    public static function createCopyCommand(
        $url,
        CopyParameters $parameters,
        WebDavClientOptions $options
    ): self {
        $destinationUrl = Url::createDestUrl($parameters->getDestinationUrl(), $options->getBaseUrl());
        return new self('COPY', $url, $parameters, $options, new Headers([
            'Destination' => (string) $destinationUrl,
            'Overwrite' => (string) $parameters->getOverwrite(),
        ]));
    }

    /**
     * @param string|UriInterface $url
     */
    public static function createMoveCommand(
        $url,
        MoveParameters $parameters,
        WebDavClientOptions $options
    ): self {
        $destinationUrl = Url::createDestUrl($parameters->getDestinationUrl(), $options->getBaseUrl());
        return new self('MOVE', $url, $parameters, $options, new Headers([
            'Destination' => (string) $destinationUrl,
        ]));
    }

    /**
     * @param string|UriInterface $url
     */
    public static function createMkcolCommand(
        $url,
        MkcolParameters $parameters,
        WebDavClientOptions $options
    ): self {
        return new self('MKCOL', $url, $parameters, $options);
    }

    /**
     * @param string|UriInterface $url
     */
    public static function createHeadCommand(
        $url,
        HeadParameters $parameters,
        WebDavClientOptions $options
    ): self {
        return new self('HEAD', $url, $parameters, $options);
    }

    /**
     * @param string|UriInterface $url
     */
    public static function createPropfindCommand(
        $url,
        PropfindParameters $parameters,
        WebDavClientOptions $options
    ): self {
        return new self('PROPFIND', $url, $parameters, $options, new Headers([
            'Depth' => (string) $parameters->getDepth(),
        ]));
    }

    /**
     * @param string|UriInterface $url
     */
    public static function createProppatchCommand(
        $url,
        ProppatchParameters $parameters,
        WebDavClientOptions $options
    ): self {
        $bodyBuilder = new ProppatchRequestBodyBuilder();
        foreach ($parameters->getPropertiesToSet() as $property) {
            $bodyBuilder->addPropetyToSet($property);
        }
        foreach ($parameters->getPropertiesToRemove() as $property) {
            $bodyBuilder->addPropetyToRemove($property);
        }
        $body = $bodyBuilder->build();
        return new self('PROPPATCH', $url, $parameters, $options, new Headers(), $body);
    }

    public function execute(): void
    {
        $this->response = $this->dispatcher->dispatch();
    }

    public function getResult(): ResponseInterface
    {
        return $this->response;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getUrl(): FullUrl
    {
        return $this->url;
    }

    /**
     * @return CopyParameters|DeleteParameters|GetParameters|HeadParameters|MkcolParameters|MoveParameters|PropfindParameters|ProppatchParameters|PutParameters
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    public function getOptions(): WebDavClientOptions
    {
        return $this->options;
    }

    public function getHeaders(): Headers
    {
        return $this->headers;
    }

    /**
     * @return resource|StreamInterface|string|null
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param string|UriInterface                  $url
     * @param mixed                                $parameters
     * @param Headers                              $headers
     * @param resource|StreamInterface|string|null $body
     */
    private function __construct(
        string $method,
        $url,
        $parameters,
        WebDavClientOptions $options,
        Headers $headers = null,
        $body = null
    ) {
        $this->method = $method;
        $this->url = Url::createRequestUrl($url, $options->getBaseUrl());
        $this->parameters = $parameters;
        $this->options = $options;
        $this->headers = $headers ?: new Headers([]);
        $this->body = $body;
        $this->dispatcher = new WebDavCommandDispatcher($this);
    }
}
