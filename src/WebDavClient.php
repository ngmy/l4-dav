<?php

declare(strict_types=1);

namespace Ngmy\PhpWebDav;

use Http\Discovery\Psr17FactoryDiscovery;
use Psr\Http\Message\UriInterface;
use RuntimeException;

class WebDavClient
{
    /**
     * Options for the WebDAV client.
     *
     * @var WebDavClientOptions
     */
    private $options;

    /**
     * WebDAV dispatcher.
     *
     * @var WebDavCommandDispatcher
     */
    private $dispatcher;

    /**
     * Create a new instance of the WebDAV Client.
     *
     * @param WebDavClientOptions $options Options for the WebDAV client
     */
    public function __construct(WebDavClientOptions $options = null)
    {
        $this->options = $options ?: (new WebDavClientOptionsBuilder())->build();
        $this->dispatcher = new WebDavCommandDispatcher($this->options);
    }

    /**
     * Perform the WebDAV GET operation.
     *
     * @param string|UriInterface $url The full URL or the URL relative to the base URL of the resource
     * @return WebDavResponse An instance of the WebDavResponse that implements the PSR-7 ResponseInterface
     */
    public function get($url): WebDavResponse
    {
        $url = Url::createRequestUrl($url, $this->options->getBaseUrl());
        $response = $this->dispatcher->dispatch(WebDavMethod::createGetMethod(), $url);
        return new WebDavResponse($response);
    }

    /**
     * Perform the WebDAV PUT operation.
     *
     * @param string|UriInterface $url        The full URL or the URL relative to the base URL of the resource
     * @param PutParameters       $parameters Parameters for the WebDAV PUT operation
     * @return WebDavResponse An instance of the WebDavResponse that implements the PSR-7 ResponseInterface
     */
    public function put($url, PutParameters $parameters): WebDavResponse
    {
        $url = Url::createRequestUrl($url, $this->options->getBaseUrl());
        $headers = new Headers();
        $headers = ContentLength::createFromFilePath($parameters->getSourcePath())->provide($headers);
        $fh = \fopen($parameters->getSourcePath(), 'r');
        if ($fh === false) {
            throw new RuntimeException(\sprintf('Failed to open the file "%s".', $parameters->getSourcePath()));
        }
        $body = Psr17FactoryDiscovery::findStreamFactory()->createStreamFromResource($fh);
        $response = $this->dispatcher->dispatch(WebDavMethod::createPutMethod(), $url, $headers, $body);
        return new WebDavResponse($response);
    }

    /**
     * Perform the WebDAV DELETE operation.
     *
     * @param string|UriInterface $url The full URL or the URL relative to the base URL of the resource
     * @return WebDavResponse An instance of the WebDavResponse that implements the PSR-7 ResponseInterface
     */
    public function delete($url): WebDavResponse
    {
        $url = Url::createRequestUrl($url, $this->options->getBaseUrl());
        $response = $this->dispatcher->dispatch(WebDavMethod::createDeleteMethod(), $url);
        return new WebDavResponse($response);
    }

    /**
     * Perform the WebDAV COPY operation.
     *
     * @param string|UriInterface $url        The full URL or the URL relative to the base URL of the resource
     * @param CopyParameters      $parameters Parameters for the WebDAV COPY operation
     * @return WebDavResponse An instance of the WebDavResponse that implements the PSR-7 ResponseInterface
     */
    public function copy($url, CopyParameters $parameters): WebDavResponse
    {
        $url = Url::createRequestUrl($url, $this->options->getBaseUrl());
        $destinationUrl = Url::createDestUrl($parameters->getDestinationUrl(), $this->options->getBaseUrl());
        $destination = new Destination($destinationUrl);
        $headers = new Headers();
        $headers = $parameters->getOverwrite()->provide($headers);
        $headers = $destination->provide($headers);
        $response = $this->dispatcher->dispatch(WebDavMethod::createCopyMethod(), $url, $headers);
        return new WebDavResponse($response);
    }

    /**
     * Perform the WebDAV MOVE operation.
     *
     * @param string|UriInterface $url        The full URL or the URL relative to the base URL of the resource
     * @param MoveParameters      $parameters Parameters for the WebDAV MOVE operation
     * @return WebDavResponse An instance of the WebDavResponse that implements the PSR-7 ResponseInterface
     */
    public function move($url, MoveParameters $parameters): WebDavResponse
    {
        $url = Url::createRequestUrl($url, $this->options->getBaseUrl());
        $destinationUrl = Url::createDestUrl($parameters->getDestinationUrl(), $this->options->getBaseUrl());
        $destination = new Destination($destinationUrl);
        $headers = new Headers();
        $headers = $destination->provide($headers);
        $response = $this->dispatcher->dispatch(WebDavMethod::createMoveMethod(), $url, $headers);
        return new WebDavResponse($response);
    }

    /**
     * Perform the WebDAV MKCOL operation.
     *
     * @param string|UriInterface $url The full URL or the URL relative to the base URL of the resource
     * @return WebDavResponse An instance of the WebDavResponse that implements the PSR-7 ResponseInterface
     */
    public function mkcol($url): WebDavResponse
    {
        $url = Url::createRequestUrl($url, $this->options->getBaseUrl());
        $response = $this->dispatcher->dispatch(WebDavMethod::createMkcolMethod(), $url);
        return new WebDavResponse($response);
    }

    /**
     * Perform the WebDAV HEAD operation.
     *
     * @param string|UriInterface $url The full URL or the URL relative to the base URL of the resource
     * @return WebDavResponse An instance of the WebDavResponse that implements the PSR-7 ResponseInterface
     */
    public function head($url): WebDavResponse
    {
        $url = Url::createRequestUrl($url, $this->options->getBaseUrl());
        $response = $this->dispatcher->dispatch(WebDavMethod::createHeadMethod(), $url);
        return new WebDavResponse($response);
    }

    /**
     * Perform the WebDAV PROPFIND operation.
     *
     * @param string|UriInterface $url        The full URL or the URL relative to the base URL of the resource
     * @param PropfindParameters  $parameters Parameters for the WebDAV PROPFIND operation
     * @return WebDavResponse An instance of the WebDavResponse that implements the PSR-7 ResponseInterface
     */
    public function propfind($url, PropfindParameters $parameters = null): WebDavResponse
    {
        $url = Url::createRequestUrl($url, $this->options->getBaseUrl());
        $parameters = $parameters ?: new PropfindParameters();
        $headers = new Headers();
        $headers = $parameters->getDepth()->provide($headers);
        $response = $this->dispatcher->dispatch(WebDavMethod::createPropfindMethod(), $url, $headers);
        return new WebDavResponse($response);
    }

    /**
     * Perform the WebDAV PROPPATCH operation.
     *
     * @param string|UriInterface $url The full URL or the URL relative to the base URL of the resource
     * @return WebDavResponse An instance of the WebDavResponse that implements the PSR-7 ResponseInterface
     */
    public function proppatch($url, ProppatchParameters $parameters): WebDavResponse
    {
        $url = Url::createRequestUrl($url, $this->options->getBaseUrl());
        $bodyBuilder = new ProppatchRequestBodyBuilder();
        foreach ($parameters->getPropertiesToSet() as $property) {
            $bodyBuilder->addPropetyToSet($property);
        }
        foreach ($parameters->getPropertiesToRemove() as $property) {
            $bodyBuilder->addPropetyToRemove($property);
        }
        $body = $bodyBuilder->build();
        $response = $this->dispatcher->dispatch(WebDavMethod::createProppatchMethod(), $url, null, $body);
        return new WebDavResponse($response);
    }
}
