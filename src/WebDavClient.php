<?php

declare(strict_types=1);

namespace Ngmy\L4Dav;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriInterface;

class WebDavClient
{
    /** @var WebDavClientOptions Options for the WebDAV client */
    private $options;

    /**
     * Create a new instance of the WebDAV Client.
     *
     * @param WebDavClientOptions $options Options for the WebDAV client
     */
    public function __construct(WebDavClientOptions $options = null)
    {
        $this->options = $options ?: (new WebDavClientOptionsBuilder())->build();
    }

    /**
     * Download a file from the WebDAV server.
     *
     * @param string|UriInterface $url        A full URL of a resource, or a URL relative to a base URL of a resource
     * @param GetParameters       $parameters Parameters for the WebDAV GET method
     * @return ResponseInterface An instance of any class that implements the PSR-7 ResponseInterface
     */
    public function get($url, GetParameters $parameters = null): ResponseInterface
    {
        $parameters = $parameters ?: (new GetParametersBuilder())->build();
        $command = Command::create('GET', $url, $parameters, $this->options);
        $command->execute();
        return $command->getResult();
    }

    /**
     * Upload a file to the WebDAV server.
     *
     * @param string|UriInterface $url        A full URL of a resource, or a URL relative to a base URL of a resource
     * @param PutParameters       $parameters Parameters for the WebDAV PUT method
     * @return ResponseInterface An instance of any class that implements the PSR-7 ResponseInterface
     */
    public function put($url, PutParameters $parameters): ResponseInterface
    {
        $command = Command::create('PUT', $url, $parameters, $this->options);
        $command->execute();
        return $command->getResult();
    }

    /**
     * Delete an item on the WebDAV server.
     *
     * @param string|UriInterface $url A full URL of a resource, or a URL relative to a base URL of a resource
     * @return ResponseInterface An instance of any class that implements the PSR-7 ResponseInterface
     */
    public function delete($url): ResponseInterface
    {
        $command = Command::create('DELETE', $url, new DeleteParameters(), $this->options);
        $command->execute();
        return $command->getResult();
    }

    /**
     * Copy an item on the WebDAV server.
     *
     * @param string|UriInterface $url        A full URL of a resource, or a URL relative to a base URL of a resource
     * @param CopyParameters      $parameters Parameters for the WebDAV COPY method
     * @return ResponseInterface An instance of any class that implements the PSR-7 ResponseInterface
     */
    public function copy($url, CopyParameters $parameters): ResponseInterface
    {
        $command = Command::create('COPY', $url, $parameters, $this->options);
        $command->execute();
        return $command->getResult();
    }

    /**
     * Rename an item on the WebDAV server.
     *
     * @param string|UriInterface $url        A full URL of a resource, or a URL relative to a base URL of a resource
     * @param MoveParameters      $parameters Parameters for the WebDAV MOVE method
     * @return ResponseInterface An instance of any class that implements the PSR-7 ResponseInterface
     */
    public function move($url, MoveParameters $parameters): ResponseInterface
    {
        $command = Command::create('MOVE', $url, $parameters, $this->options);
        $command->execute();
        return $command->getResult();
    }

    /**
     * Make a directory on the WebDAV server.
     *
     * @param string|UriInterface $url A full URL of a resource, or a URL relative to a base URL of a resource
     * @return ResponseInterface An instance of any class that implements the PSR-7 ResponseInterface
     */
    public function mkcol($url): ResponseInterface
    {
        $command = Command::create('MKCOL', $url, new MkcolParameters(), $this->options);
        $command->execute();
        return $command->getResult();
    }

    /**
     * Check the existence of an item on the WebDAV server.
     *
     * @param string|UriInterface $url A full URL of a resource, or a URL relative to a base URL of a resource
     * @return HeadResponse An instance of the HeadResponse that implements the PSR-7 ResponseInterface
     */
    public function head($url): HeadResponse
    {
        $command = Command::create('HEAD', $url, new HeadParameters(), $this->options);
        $command->execute();
        \assert($command->getResult() instanceof HeadResponse);
        return $command->getResult();
    }

    /**
     * List contents of a directory on the WebDAV server.
     *
     * @param string|UriInterface $url        A full URL of a resource, or a URL relative to a base URL of a resource
     * @param PropfindParameters  $parameters Parameters for the WebDAV PROPFIND method
     * @return PropfindResponse An instance of the PropfindResponse that implements the PSR-7 ResponseInterface
     */
    public function propfind($url, PropfindParameters $parameters = null): PropfindResponse
    {
        $parameters = $parameters ?: (new PropfindParametersBuilder())->build();
        $command = Command::create('PROPFIND', $url, $parameters, $this->options);
        $command->execute();
        \assert($command->getResult() instanceof PropfindResponse);
        return $command->getResult();
    }

    /**
     * @param string|UriInterface $url A full URL of a resource, or a URL relative to a base URL of a resource
     * @return ResponseInterface An instance of any class that implements the PSR-7 ResponseInterface
     */
    public function proppatch($url, ProppatchParameters $parameters): ResponseInterface
    {
        $command = Command::create('PROPPATCH', $url, $parameters, $this->options);
        $command->execute();
        return $command->getResult();
    }
}
