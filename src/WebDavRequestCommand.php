<?php

declare(strict_types=1);

namespace Ngmy\PhpWebDav;

use Http\Discovery\Psr17FactoryDiscovery;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;
use RuntimeException;

class WebDavRequestCommand
{
    /** @var WebDavMethod */
    private $method;
    /** @var FullUrl */
    private $url;
    /** @var Headers */
    private $headers;
    /** @var Body */
    private $body;
    /** @var ResponseInterface */
    private $response;

    /**
     * @param string|UriInterface $url
     */
    public static function createGetCommand(
        WebDavClientOptions $options,
        $url
    ): self {
        return new self($options, WebDavMethod::createGetMethod(), $url);
    }

    /**
     * @param string|UriInterface $url
     * @throws RuntimeException
     */
    public static function createPutCommand(
        WebDavClientOptions $options,
        $url,
        PutParameters $parameters
    ): self {
        $headers = new Headers();
        $headers = ContentLength::createFromFilePath($parameters->getSourcePath())->provide($headers);
        $fh = \fopen($parameters->getSourcePath(), 'r');
        if ($fh === false) {
            throw new RuntimeException(\sprintf('Failed to open the file "%s".', $parameters->getSourcePath()));
        }
        $body = Psr17FactoryDiscovery::findStreamFactory()->createStreamFromResource($fh);
        return new self($options, WebDavMethod::createPutMethod(), $url, $headers, $body);
    }

    /**
     * @param string|UriInterface $url
     */
    public static function createDeleteCommand(
        WebDavClientOptions $options,
        $url
    ): self {
        return new self($options, WebDavMethod::createDeleteMethod(), $url);
    }

    /**
     * @param string|UriInterface $url
     */
    public static function createCopyCommand(
        WebDavClientOptions $options,
        $url,
        CopyParameters $parameters
    ): self {
        $destinationUrl = Url::createDestUrl($parameters->getDestinationUrl(), $options->getBaseUrl());
        $headers = new Headers();
        $headers = $parameters->getOverwrite()->provide($headers);
        $headers = (new Destination($destinationUrl))->provide($headers);
        return new self($options, WebDavMethod::createCopyMethod(), $url, $headers);
    }

    /**
     * @param string|UriInterface $url
     */
    public static function createMoveCommand(
        WebDavClientOptions $options,
        $url,
        MoveParameters $parameters
    ): self {
        $destinationUrl = Url::createDestUrl($parameters->getDestinationUrl(), $options->getBaseUrl());
        $headers = new Headers();
        $headers = (new Destination($destinationUrl))->provide($headers);
        return new self($options, WebDavMethod::createMoveMethod(), $url, $headers);
    }

    /**
     * @param string|UriInterface $url
     */
    public static function createMkcolCommand(
        WebDavClientOptions $options,
        $url
    ): self {
        return new self($options, WebDavMethod::createMkcolMethod(), $url);
    }

    /**
     * @param string|UriInterface $url
     */
    public static function createHeadCommand(
        WebDavClientOptions $options,
        $url
    ): self {
        return new self($options, WebDavMethod::createHeadMethod(), $url);
    }

    /**
     * @param string|UriInterface $url
     */
    public static function createPropfindCommand(
        WebDavClientOptions $options,
        $url,
        PropfindParameters $parameters
    ): self {
        $headers = new Headers();
        $headers = $parameters->getDepth()->provide($headers);
        return new self($options, WebDavMethod::createPropfindMethod(), $url, $headers);
    }

    /**
     * @param string|UriInterface $url
     */
    public static function createProppatchCommand(
        WebDavClientOptions $options,
        $url,
        ProppatchParameters $parameters
    ): self {
        $bodyBuilder = new ProppatchRequestBodyBuilder();
        foreach ($parameters->getPropertiesToSet() as $property) {
            $bodyBuilder->addPropetyToSet($property);
        }
        foreach ($parameters->getPropertiesToRemove() as $property) {
            $bodyBuilder->addPropetyToRemove($property);
        }
        $body = $bodyBuilder->build();
        return new self($options, WebDavMethod::createProppatchMethod(), $url, null, $body);
    }

    public function execute(WebDavRequestDispatcher $dispatcher): void
    {
        $this->response = $dispatcher->dispatch($this);
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

    public function getHeaders(): Headers
    {
        return $this->headers;
    }

    public function getBody(): Body
    {
        return $this->body;
    }

    /**
     * @param string|UriInterface                                                                                                                              $url
     * @param resource|StreamInterface|string|null                                                                                                             $body
     */
    private function __construct(
        WebDavClientOptions $options,
        WebDavMethod $method,
        $url,
        Headers $headers = null,
        $body = null
    ) {
        $this->method = $method;
        $this->url = Url::createRequestUrl($url, $options->getBaseUrl());
        $this->headers = \is_null($headers)
            ? $options->getDefaultRequestHeaders()
            : $options->getDefaultRequestHeaders()->withHeaders($headers);
        $this->body = new Body($body);
    }
}
