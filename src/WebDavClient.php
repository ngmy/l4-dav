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
     * Create a new Client class object.
     */
    public function __construct(WebDavClientOptions $options = null)
    {
        $this->options = $options ?: (new WebDavClientOptionsBuilder())->build();
    }

    /**
     * Download a file from the WebDAV server.
     *
     * @param string|UriInterface $srcUri   The source path of a file.
     * @param string              $destPath The destination path of a file.
     * @return ResponseInterface Returns a Response class object.
     */
    public function download($srcUri, string $destPath): ResponseInterface
    {
        $command = Command::create(__FUNCTION__, $this->options, $srcUri, $destPath);
        $command->execute();
        return $command->getResult();
    }

    /**
     * Upload a file to the WebDAV server.
     *
     * @param string              $srcPath The source path of a file.
     * @param string|UriInterface $destUri The destination path of a file.
     * @return ResponseInterface Returns a Response class object.
     */
    public function upload(string $srcPath, $destUri): ResponseInterface
    {
        $command = Command::create(__FUNCTION__, $this->options, $srcPath, $destUri);
        $command->execute();
        return $command->getResult();
    }

    /**
     * Delete an item on the WebDAV server.
     *
     * @param string $uri The path of an item.
     * @return ResponseInterface Returns a Response class object.
     */
    public function delete(string $uri): ResponseInterface
    {
        $command = Command::create(__FUNCTION__, $this->options, $uri);
        $command->execute();
        return $command->getResult();
    }

    /**
     * Copy an item on the WebDAV server.
     *
     * @param string|UriInterface $srcUri  The source path of an item.
     * @param string|UriInterface $destUri The destination path of an item.
     * @return ResponseInterface Returns a Response class object.
     */
    public function copy($srcUri, $destUri): ResponseInterface
    {
        $command = Command::create(__FUNCTION__, $this->options, $srcUri, $destUri);
        $command->execute();
        return $command->getResult();
    }

    /**
     * Rename an item on the WebDAV server.
     *
     * @param string|UriInterface $srcUri  The source path of an item.
     * @param string|UriInterface $destUri The destination path of an item.
     * @return ResponseInterface Returns a Response class object.
     */
    public function move($srcUri, $destUri): ResponseInterface
    {
        $command = Command::create(__FUNCTION__, $this->options, $srcUri, $destUri);
        $command->execute();
        return $command->getResult();
    }

    /**
     * Make a directory on the WebDAV server.
     *
     * @param string|UriInterface $uri The directory path.
     * @return ResponseInterface Returns a Response class object.
     */
    public function makeDirectory($uri): ResponseInterface
    {
        $command = Command::create(__FUNCTION__, $this->options, $uri);
        $command->execute();
        return $command->getResult();
    }

    /**
     * Check the existence of an item on the WebDAV server.
     *
     * @param string|UriInterface $uri The path of an item.
     * @return ExistsResponse Returns true if an item exists.
     */
    public function exists($uri): ExistsResponse
    {
        $command = Command::create(__FUNCTION__, $this->options, $uri);
        $command->execute();
        \assert($command->getResult() instanceof ExistsResponse);
        return $command->getResult();
    }

    /**
     * List contents of a directory on the WebDAV server.
     *
     * @param string|UriInterface $uri The directory path.
     * @return ListResponse Returns a list of contents of the directory.
     */
    public function list($uri): ListResponse
    {
        $command = Command::create(__FUNCTION__, $this->options, $uri);
        $command->execute();
        \assert($command->getResult() instanceof ListResponse);
        return $command->getResult();
    }
}
