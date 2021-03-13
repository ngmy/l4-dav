<?php

declare(strict_types=1);

namespace Ngmy\WebDav\Request;

use Http\Discovery\Psr17FactoryDiscovery;
use Ngmy\WebDav\Client;
use Ngmy\WebDav\Request;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriInterface;
use RuntimeException;

class Command
{
    /** @var Client\Options */
    private $options;
    /** @var Request\Method */
    private $method;
    /** @var Request\Url\Full */
    private $url;
    /** @var Request\Headers */
    private $headers;
    /** @var Request\Body */
    private $body;
    /** @var ResponseInterface */
    private $response;

    /**
     * @param string|UriInterface $url
     */
    public static function createGetCommand(
        Client\Options $options,
        $url
    ): self {
        return new self($options, Request\Method::createGetMethod(), $url);
    }

    /**
     * @param string|UriInterface $url
     * @throws RuntimeException
     */
    public static function createPutCommand(
        Client\Options $options,
        $url,
        Request\Parameters\Put $parameters
    ): self {
        $headers = new Request\Headers();
        $headers = Request\Header\ContentLength::createFromFilePath($parameters->getSourcePath())->provide($headers);
        $fh = \fopen($parameters->getSourcePath(), 'r');
        if ($fh === false) {
            throw new RuntimeException(\sprintf('Failed to open the file "%s".', $parameters->getSourcePath()));
        }
        $body = new Request\Body(Psr17FactoryDiscovery::findStreamFactory()->createStreamFromResource($fh));
        return new self($options, Request\Method::createPutMethod(), $url, $headers, $body);
    }

    /**
     * @param string|UriInterface $url
     */
    public static function createDeleteCommand(
        Client\Options $options,
        $url
    ): self {
        return new self($options, Request\Method::createDeleteMethod(), $url);
    }

    /**
     * @param string|UriInterface $url
     */
    public static function createCopyCommand(
        Client\Options $options,
        $url,
        Request\Parameters\Copy $parameters
    ): self {
        $headers = new Request\Headers();
        $headers = $parameters->getOverwrite()->provide($headers);
        $headers = Request\Header\Destination::createFromUrl($parameters->getDestinationUrl(), $options->getBaseUrl())
            ->provide($headers);
        return new self($options, Request\Method::createCopyMethod(), $url, $headers);
    }

    /**
     * @param string|UriInterface $url
     */
    public static function createMoveCommand(
        Client\Options $options,
        $url,
        Request\Parameters\Move $parameters
    ): self {
        $headers = new Request\Headers();
        $headers = Request\Header\Destination::createFromUrl($parameters->getDestinationUrl(), $options->getBaseUrl())
            ->provide($headers);
        return new self($options, Request\Method::createMoveMethod(), $url, $headers);
    }

    /**
     * @param string|UriInterface $url
     */
    public static function createMkcolCommand(
        Client\Options $options,
        $url
    ): self {
        return new self($options, Request\Method::createMkcolMethod(), $url);
    }

    /**
     * @param string|UriInterface $url
     */
    public static function createHeadCommand(
        Client\Options $options,
        $url
    ): self {
        return new self($options, Request\Method::createHeadMethod(), $url);
    }

    /**
     * @param string|UriInterface $url
     */
    public static function createPropfindCommand(
        Client\Options $options,
        $url,
        Request\Parameters\Propfind $parameters
    ): self {
        $headers = new Request\Headers();
        $headers = $parameters->getDepth()->provide($headers);
        return new self($options, Request\Method::createPropfindMethod(), $url, $headers);
    }

    /**
     * @param string|UriInterface $url
     */
    public static function createProppatchCommand(
        Client\Options $options,
        $url,
        Request\Parameters\Proppatch $parameters
    ): self {
        $bodyBuilder = new Request\Body\Builder\Proppatch();
        foreach ($parameters->getPropertiesToSet() as $property) {
            $bodyBuilder->addPropetyToSet($property);
        }
        foreach ($parameters->getPropertiesToRemove() as $property) {
            $bodyBuilder->addPropetyToRemove($property);
        }
        $body = $bodyBuilder->build();
        return new self($options, Request\Method::createProppatchMethod(), $url, null, $body);
    }

    public function execute(Request\Dispatcher $dispatcher): void
    {
        $this->response = $dispatcher->dispatch($this);
    }

    public function getResult(): ResponseInterface
    {
        return $this->response;
    }

    public function getOptions(): Client\Options
    {
        return $this->options;
    }

    public function getMethod(): Request\Method
    {
        return $this->method;
    }

    public function getUrl(): Request\Url\Full
    {
        return $this->url;
    }

    public function getHeaders(): Headers
    {
        return $this->headers;
    }

    public function getBody(): Request\Body
    {
        return $this->body;
    }

    /**
     * @param string|UriInterface $url
     */
    private function __construct(
        Client\Options $options,
        Request\Method $method,
        $url,
        Headers $headers = null,
        Request\Body $body = null
    ) {
        $this->options = $options;
        $this->method = $method;
        $this->url = Request\Url::createRequestUrl($url, $options->getBaseUrl());
        $this->headers = \is_null($headers)
            ? $options->getDefaultRequestHeaders()
            : $options->getDefaultRequestHeaders()->withHeaders($headers);
        $this->body = $body ?: new Request\Body();
    }
}
