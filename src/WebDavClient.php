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
     *
     * @return void
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
        return (new DownloadCommand($this->options, $srcUri, $destPath))->execute();
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
        return (new UploadCommand($this->options, $srcPath, $destUri))->execute();
    }

    /**
     * Delete an item on the WebDAV server.
     *
     * @param string $uri The path of an item.
     * @return ResponseInterface Returns a Response class object.
     */
    public function delete(string $uri): ResponseInterface
    {
        return (new DeleteCommand($this->options, $uri))->execute();
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
        return (new CopyCommand($this->options, $srcUri, $destUri))->execute();
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
        return (new MoveCommand($this->options, $srcUri, $destUri))->execute();
    }

    /**
     * Make a directory on the WebDAV server.
     *
     * @param string|UriInterface $uri The directory path.
     * @return ResponseInterface Returns a Response class object.
     */
    public function makeDirectory($uri): ResponseInterface
    {
        return (new MakeDirectoryCommand($this->options, $uri))->execute();
    }

    /**
     * Check the existence of an item on the WebDAV server.
     *
     * @param string|UriInterface $uri The path of an item.
     * @return ExistsResponse Returns true if an item exists.
     */
    public function exists($uri): ExistsResponse
    {
        $response = (new ExistsCommand($this->options, $uri))->execute();
        \assert($response instanceof ExistsResponse);
        return $response;
    }

    /**
     * List contents of a directory on the WebDAV server.
     *
     * @param string|UriInterface $uri The directory path.
     * @return ListResponse Returns a list of contents of the directory.
     */
    public function list($uri): ListResponse
    {
        $response = (new ListCommand($this->options, $uri))->execute();
        \assert($response instanceof ListResponse);
        return $response;
    }
}
