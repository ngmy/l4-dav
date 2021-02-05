<?php

declare(strict_types=1);

namespace Ngmy\L4Dav;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriInterface;

class WebDavClient
{
    /** @var WebDavClientOptions */
    private $options;

    /**
     * Create new WebDAV client.
     *
     * @param WebDavClientOptions $options WebDavClient options
     */
    public function __construct(WebDavClientOptions $options = null)
    {
        $this->options = $options ?: (new WebDavClientOptionsBuilder())->build();
    }

    /**
     * Download a file from the WebDAV server.
     *
     * @param string|UriInterface $requestUri   The source path of a file
     * @param GetParameters $parameters
     * @return ResponseInterface Returns a Response class object
     */
    public function get($requestUri, GetParameters $parameters = null): ResponseInterface
    {
        $parameters = $parameters ?: (new GetParametersBuilder())->build();
        $command = Command::create('GET', $requestUri, $parameters, $this->options);
        $command->execute();
        return $command->getResult();
    }

    /**
     * Upload a file to the WebDAV server.
     *
     * @param string|UriInterface $requestUri The destination path of a file
     * @param PutParameters $parameters
     * @return ResponseInterface Returns a Response class object
     */
    public function put($requestUri, PutParameters $parameters): ResponseInterface
    {
        $command = Command::create('PUT', $requestUri, $parameters, $this->options);
        $command->execute();
        return $command->getResult();
    }

    /**
     * Delete an item on the WebDAV server.
     *
     * @param string|UriInterface $requestUri The path of an item
     * @return ResponseInterface Returns a Response class object
     */
    public function delete($requestUri): ResponseInterface
    {
        $command = Command::create('DELETE', $requestUri, new DeleteParameters(), $this->options);
        $command->execute();
        return $command->getResult();
    }

    /**
     * Copy an item on the WebDAV server.
     *
     * @param string|UriInterface $requestUri    The source path of an item
     * @param CopyParameters $parameters
     * @return ResponseInterface Returns a Response class object
     */
    public function copy($requestUri, CopyParameters $parameters): ResponseInterface
    {
        $command = Command::create('COPY', $requestUri, $parameters, $this->options);
        $command->execute();
        return $command->getResult();
    }

    /**
     * Rename an item on the WebDAV server.
     *
     * @param string|UriInterface $requestUri  The source path of an item
     * @param MoveParameters $parameters
     * @return ResponseInterface Returns a Response class object
     */
    public function move($requestUri, MoveParameters $parameters): ResponseInterface
    {
        $command = Command::create('MOVE', $requestUri, $parameters, $this->options);
        $command->execute();
        return $command->getResult();
    }

    /**
     * Make a directory on the WebDAV server.
     *
     * @param string|UriInterface $requestUri The directory path
     * @return ResponseInterface Returns a Response class object
     */
    public function mkcol($requestUri): ResponseInterface
    {
        $command = Command::create('MKCOL', $requestUri, new MkcolParameters(), $this->options);
        $command->execute();
        return $command->getResult();
    }

    /**
     * Check the existence of an item on the WebDAV server.
     *
     * @param string|UriInterface $requestUri The path of an item
     * @return HeadResponse Returns true if an item exists
     */
    public function head($requestUri): HeadResponse
    {
        $command = Command::create('HEAD', $requestUri, new HeadParameters(), $this->options);
        $command->execute();
        \assert($command->getResult() instanceof HeadResponse);
        return $command->getResult();
    }

    /**
     * List contents of a directory on the WebDAV server.
     *
     * @param string|UriInterface $requestUri   The directory path
     * @param PropfindParameters  $parameters
     * @return PropfindResponse Returns a list of contents of the directory
     */
    public function propfind($requestUri, PropfindParameters $parameters = null): PropfindResponse
    {
        $parameters = $parameters ?: (new PropfindParametersBuilder())->build();
        $command = Command::create('PROPFIND', $requestUri, $parameters, $this->options);
        $command->execute();
        \assert($command->getResult() instanceof PropfindResponse);
        return $command->getResult();
    }

    /**
     * @param string|UriInterface $requestUri
     * @param ProppatchParameters $parameters
     */
    public function proppatch($requestUri, ProppatchParameters $parameters): ResponseInterface
    {
        $command = Command::create('PROPPATCH', $requestUri, $parameters, $this->options);
        $command->execute();
        return $command->getResult();
    }
}
