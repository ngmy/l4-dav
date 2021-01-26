<?php

declare(strict_types=1);

namespace Ngmy\L4Dav;

use GuzzleHttp\Psr7\{
    Request,
    Stream,
    Uri,
};
use Http\Client\HttpClient;
use League\Uri\UriResolver;
use Psr\Http\Message\{
    ResponseInterface,
    UriInterface,
};
use RuntimeException;

class WebDavClient
{
    /** @var WebDavClientOptions */
    private $options;
    /** @var HttpClient */
    private $httpClient;

    /**
     * Create a new Client class object.
     *
     * @param WebDavClientOptions|null $options
     * @param HttpClient|null          $httpClient
     * @return void
     */
    public function __construct(?WebDavClientOptions $options = null, ?HttpClient $httpClient = null)
    {
        $this->options = $options ?? new WebDavClientOptions();
        $this->httpClient = $httpClient ?? new CurlHttpClientWrapper($this->options);
    }

    /**
     * Download a file from the WebDAV server.
     *
     * @param string $srcUri   The source path of a file.
     * @param string $destPath The destination path of a file.
     * @throws RuntimeException
     * @return ResponseInterface Returns a Response class object.
     */
    public function download(string $srcUri, string $destPath): ResponseInterface
    {
        $srcUri = \is_null($this->options->getBaseUri())
            ? new Uri($srcUri)
            : UriResolver::resolve(new Uri($srcUri), $this->options->getBaseUri());
        \assert($srcUri instanceof UriInterface);

        $headers = $this->options->getDefaultRequestHeaders()->toArray();
        $request = new Request('GET', $srcUri, $headers);
        $response = $this->httpClient->sendRequest($request);

        \file_put_contents($destPath, $response->getBody()->getContents());

        return $response;
    }

    /**
     * Upload a file to the WebDAV server.
     *
     * @param string $srcPath The source path of a file.
     * @param string $destUri The destination path of a file.
     * @throws RuntimeException
     * @return ResponseInterface Returns a Response class object.
     */
    public function upload(string $srcPath, string $destUri): ResponseInterface
    {
        $destUri = \is_null($this->options->getBaseUri())
            ? new Uri($destUri)
            : UriResolver::resolve(new Uri($destUri), $this->options->getBaseUri());
        \assert($destUri instanceof UriInterface);

        $fileSize = \filesize($srcPath);
        $fh = \fopen($srcPath, 'r');
        if ($fh === false) {
            throw new RuntimeException('Failed to open file (' . $srcPath. ')');
        }
        $stream = new Stream($fh);
        $body = $stream;

        $headers = $this->options->getDefaultRequestHeaders()
            ->addHeader('Content-Length', (string) $fileSize)
            ->toArray();
        $request = new Request('PUT', $destUri, $headers, $body);
        return $this->httpClient->sendRequest($request);
    }

    /**
     * Delete an item on the WebDAV server.
     *
     * @param string $uri The path of an item.
     * @return ResponseInterface Returns a Response class object.
     */
    public function delete(string $uri): ResponseInterface
    {
        $uri = \is_null($this->options->getBaseUri())
            ? new Uri($uri)
            : UriResolver::resolve(new Uri($uri), $this->options->getBaseUri());
        \assert($uri instanceof UriInterface);

        $headers = $this->options->getDefaultRequestHeaders()->toArray();
        $request = new Request('DELETE', $uri, $headers);
        return $this->httpClient->sendRequest($request);
    }

    /**
     * Copy an item on the WebDAV server.
     *
     * @param string $srcUri  The source path of an item.
     * @param string $destUri The destination path of an item.
     * @return ResponseInterface Returns a Response class object.
     */
    public function copy(string $srcUri, string $destUri): ResponseInterface
    {
        $srcUri = \is_null($this->options->getBaseUri())
            ? new Uri($srcUri)
            : UriResolver::resolve(new Uri($srcUri), $this->options->getBaseUri());
        $destUri = \is_null($this->options->getBaseUri())
            ? new Uri($destUri)
            : UriResolver::resolve(new Uri($destUri), $this->options->getBaseUri());
        \assert($srcUri instanceof UriInterface);
        \assert($destUri instanceof UriInterface);

        $headers = $this->options->getDefaultRequestHeaders()
            ->addHeader('Destination', (string) $destUri)
            ->toArray();
        $request = new Request('COPY', $srcUri, $headers);
        return $this->httpClient->sendRequest($request);
    }

    /**
     * Rename an item on the WebDAV server.
     *
     * @param string $srcUri  The source path of an item.
     * @param string $destUri The destination path of an item.
     * @return ResponseInterface Returns a Response class object.
     */
    public function move(string $srcUri, string $destUri): ResponseInterface
    {
        $srcUri = \is_null($this->options->getBaseUri())
            ? new Uri($srcUri)
            : UriResolver::resolve(new Uri($srcUri), $this->options->getBaseUri());
        $destUri = \is_null($this->options->getBaseUri())
            ? new Uri($destUri)
            : UriResolver::resolve(new Uri($destUri), $this->options->getBaseUri());
        \assert($srcUri instanceof UriInterface);
        \assert($destUri instanceof UriInterface);

        $headers = $this->options->getDefaultRequestHeaders()
            ->addHeader('Destination', (string) $destUri)
            ->toArray();
        $request = new Request('MOVE', $srcUri, $headers);
        return $this->httpClient->sendRequest($request);
    }

    /**
     * Make a directory on the WebDAV server.
     *
     * @param string $uri The directory path.
     * @return ResponseInterface Returns a Response class object.
     */
    public function makeDirectory(string $uri): ResponseInterface
    {
        $uri = \is_null($this->options->getBaseUri())
            ? new Uri($uri)
            : UriResolver::resolve(new Uri($uri), $this->options->getBaseUri());
        \assert($uri instanceof UriInterface);

        $headers = $this->options->getDefaultRequestHeaders()->toArray();
        $request = new Request('MKCOL', $uri, $headers);
        return $this->httpClient->sendRequest($request);
    }

    /**
     * Check the existence of an item on the WebDAV server.
     *
     * @param string $uri The path of an item.
     * @return ExistsResponse Returns true if an item exists.
     */
    public function exists(string $uri): ExistsResponse
    {
        $uri = \is_null($this->options->getBaseUri())
            ? new Uri($uri)
            : UriResolver::resolve(new Uri($uri), $this->options->getBaseUri());
        \assert($uri instanceof UriInterface);

        $headers = $this->options->getDefaultRequestHeaders()->toArray();
        $request = new Request('HEAD', $uri, $headers);
        $response = $this->httpClient->sendRequest($request);

        return new ExistsResponse($response);
    }

    /**
     * List contents of a directory on the WebDAV server.
     *
     * @param string $uri The directory path.
     * @return ListResponse Returns a list of contents of the directory.
     */
    public function list(string $uri): ListResponse
    {
        $uri = \is_null($this->options->getBaseUri())
            ? new Uri($uri)
            : UriResolver::resolve(new Uri($uri), $this->options->getBaseUri());
        \assert($uri instanceof UriInterface);

        $headers = $this->options->getDefaultRequestHeaders()
            ->addHeaders((new ListOptions())->getHeaders())
            ->toArray();
        $request = new Request('PROPFIND', $uri, $headers);
        $response = $this->httpClient->sendRequest($request);
        return (new ListResponseParser())->parse($response);
    }
}
