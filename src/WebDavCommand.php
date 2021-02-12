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
    /** @var WebDavMethod */
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
        return new self(WebDavMethod::createGetMethod(), $url, $parameters, $options);
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
        $headers = new Headers();
        $headers = ContentLength::createFromFilePath($parameters->getSourcePath())->provide($headers);
        return new self(WebDavMethod::createPutMethod(), $url, $parameters, $options, $headers, $body);
    }

    /**
     * @param string|UriInterface $url
     */
    public static function createDeleteCommand(
        $url,
        DeleteParameters $parameters,
        WebDavClientOptions $options
    ): self {
        return new self(WebDavMethod::createDeleteMethod(), $url, $parameters, $options);
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
        $destination = new Destination($destinationUrl);
        $headers = new Headers();
        $headers = $parameters->getOverwrite()->provide($headers);
        $headers = $destination->provide($headers);
        return new self(WebDavMethod::createCopyMethod(), $url, $parameters, $options, $headers);
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
        $destination = new Destination($destinationUrl);
        $headers = new Headers();
        $headers = $destination->provide($headers);
        return new self(WebDavMethod::createMoveMethod(), $url, $parameters, $options, $headers);
    }

    /**
     * @param string|UriInterface $url
     */
    public static function createMkcolCommand(
        $url,
        MkcolParameters $parameters,
        WebDavClientOptions $options
    ): self {
        return new self(WebDavMethod::createMkcolMethod(), $url, $parameters, $options);
    }

    /**
     * @param string|UriInterface $url
     */
    public static function createHeadCommand(
        $url,
        HeadParameters $parameters,
        WebDavClientOptions $options
    ): self {
        return new self(WebDavMethod::createHeadMethod(), $url, $parameters, $options);
    }

    /**
     * @param string|UriInterface $url
     */
    public static function createPropfindCommand(
        $url,
        PropfindParameters $parameters,
        WebDavClientOptions $options
    ): self {
        $headers = new Headers();
        $headers = $parameters->getDepth()->provide($headers);
        return new self(WebDavMethod::createPropfindMethod(), $url, $parameters, $options, $headers);
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
        return new self(WebDavMethod::createProppatchMethod(), $url, $parameters, $options, new Headers(), $body);
    }

    public function execute(): void
    {
        $this->response = $this->dispatcher->dispatch();
    }

    public function getResult(): ResponseInterface
    {
        return $this->response;
    }

    public function getMethod(): WebDavMethod
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
        WebDavMethod $method,
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
