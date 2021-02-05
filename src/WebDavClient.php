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
     * @param string|UriInterface $srcUri   The source path of a file
     * @param GetParameters $parameters
     * @return ResponseInterface Returns a Response class object
     */
    public function get($srcUri, GetParameters $parameters): ResponseInterface
    {
        $command = Command::create('GET', $this->options, $srcUri, $parameters->destPath());
        $command->execute();
        return $command->getResult();
    }

    /**
     * Upload a file to the WebDAV server.
     *
     * @param string|UriInterface $destUri The destination path of a file
     * @param PutParameters $parameters
     * @return ResponseInterface Returns a Response class object
     */
    public function put($destUri, PutParameters $parameters): ResponseInterface
    {
        $command = Command::create('PUT', $this->options, $destUri, $parameters->srcPath());
        $command->execute();
        return $command->getResult();
    }

    /**
     * Delete an item on the WebDAV server.
     *
     * @param string|UriInterface $uri The path of an item
     * @return ResponseInterface Returns a Response class object
     */
    public function delete($uri): ResponseInterface
    {
        $command = Command::create('DELETE', $this->options, $uri);
        $command->execute();
        return $command->getResult();
    }

    /**
     * Copy an item on the WebDAV server.
     *
     * @param string|UriInterface $srcUri    The source path of an item
     * @param CopyParameters $parameters
     * @return ResponseInterface Returns a Response class object
     */
    public function copy($srcUri, CopyParameters $parameters): ResponseInterface
    {
        $command = Command::create('COPY', $this->options, $srcUri, $parameters->destUri(), $parameters->overwrite());
        $command->execute();
        return $command->getResult();
    }

    /**
     * Rename an item on the WebDAV server.
     *
     * @param string|UriInterface $srcUri  The source path of an item
     * @param MoveParameters $parameters
     * @return ResponseInterface Returns a Response class object
     */
    public function move($srcUri, MoveParameters $parameters): ResponseInterface
    {
        $command = Command::create('MOVE', $this->options, $srcUri, $parameters->destUri());
        $command->execute();
        return $command->getResult();
    }

    /**
     * Make a directory on the WebDAV server.
     *
     * @param string|UriInterface $uri The directory path
     * @return ResponseInterface Returns a Response class object
     */
    public function mkcol($uri): ResponseInterface
    {
        $command = Command::create('MKCOL', $this->options, $uri);
        $command->execute();
        return $command->getResult();
    }

    /**
     * Check the existence of an item on the WebDAV server.
     *
     * @param string|UriInterface $uri The path of an item
     * @return HeadResponse Returns true if an item exists
     */
    public function head($uri): HeadResponse
    {
        $command = Command::create('HEAD', $this->options, $uri);
        $command->execute();
        \assert($command->getResult() instanceof HeadResponse);
        return $command->getResult();
    }

    /**
     * List contents of a directory on the WebDAV server.
     *
     * @param string|UriInterface $uri   The directory path
     * @param PropfindParameters  $parameters
     * @return PropfindResponse Returns a list of contents of the directory
     */
    public function propfind($uri, PropfindParameters $parameters): PropfindResponse
    {
        $command = Command::create('PROPFIND', $this->options, $uri, $parameters->depth());
        $command->execute();
        \assert($command->getResult() instanceof PropfindResponse);
        return $command->getResult();
    }

    /**
     * @param string|UriInterface $uri
     * @param ProppatchParameters $parameters
     */
    public function proppatch($uri, ProppatchParameters $parameters): ResponseInterface
    {
        $command = Command::create('Proppatch', $this->options, $uri, $parameters->propertiesToSet(), $parameters->propertiesToRemove());
        $command->execute();
        return $command->getResult();
    }
}
