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
use Psr\Http\Message\ResponseInterface;
use RuntimeException;
use SimpleXMLElement;

class WebDavClient
{
    /** @var WebDavClientParameters */
    private $parameters;
    /** @var HttpClient */
    private $httpClient;

    /**
     * Create a new Client class object.
     *
     * @param WebDavClientParameters|null $parameters
     * @param HttpClient|null             $httpClient
     * @return void
     */
    public function __construct(?WebDavClientParameters $parameters = null, ?HttpClient $httpClient = null)
    {
        $this->parameters = $parameters ?? new WebDavClientParameters();
        $this->httpClient = $httpClient ?? new CurlHttpClientWrapper($this->parameters);
    }

    /**
     * Download a file from the WebDAV server.
     *
     * @param string $srcPath  The source path of a file.
     * @param string $destPath The destination path of a file.
     * @throws RuntimeException
     * @return ResponseInterface Returns a Response class object.
     */
    public function download(string $srcPath, string $destPath): ResponseInterface
    {
        $srcUri = \is_null($this->parameters->getBaseAddress())
            ? new Uri($srcPath)
            : UriResolver::resolve(new Uri($srcPath), $this->parameters->getBaseAddress());

        $request = new Request('GET', $srcUri);
        $response = $this->httpClient->sendRequest($request);

        \file_put_contents($destPath, $response->getBody()->getContents());

        return $response;
    }

    /**
     * Upload a file to the WebDAV server.
     *
     * @param string $srcPath  The source path of a file.
     * @param string $destPath The destination path of a file.
     * @throws RuntimeException
     * @return ResponseInterface Returns a Response class object.
     */
    public function upload(string $srcPath, string $destPath): ResponseInterface
    {
        $destUri = \is_null($this->parameters->getBaseAddress())
            ? new Uri($destPath)
            : UriResolver::resolve(new Uri($destPath), $this->parameters->getBaseAddress());

        $fileSize = \filesize($srcPath);
        $fh = \fopen($srcPath, 'r');
        if ($fh === false) {
            throw new RuntimeException('Failed to open file (' . $srcPath . ')');
        }
        $fileStream = new Stream($fh);
        $body = $fileStream;

        $request = new Request('PUT', $destUri, ['Content-Length' => $fileSize], $body);
        $response = $this->httpClient->sendRequest($request);

        return $response;
    }

    /**
     * Delete an item on the WebDAV server.
     *
     * @param string $path The path of an item.
     * @return ResponseInterface Returns a Response class object.
     */
    public function delete(string $path): ResponseInterface
    {
        $uri = \is_null($this->parameters->getBaseAddress())
            ? new Uri($path)
            : UriResolver::resolve(new Uri($path), $this->parameters->getBaseAddress());

        $request = new Request('DELETE', $uri);
        return $this->httpClient->sendRequest($request);
    }

    /**
     * Copy an item on the WebDAV server.
     *
     * @param string $srcPath  The source path of an item.
     * @param string $destPath The destination path of an item.
     * @return ResponseInterface Returns a Response class object.
     */
    public function copy(string $srcPath, string $destPath): ResponseInterface
    {
        $srcUri = \is_null($this->parameters->getBaseAddress())
            ? new Uri($srcPath)
            : UriResolver::resolve(new Uri($srcPath), $this->parameters->getBaseAddress());
        $destUri = \is_null($this->parameters->getBaseAddress())
            ? new Uri($destPath)
            : UriResolver::resolve(new Uri($destPath), $this->parameters->getBaseAddress());

        $headers['Destination'] = (string) $destUri;
        $request = new Request('COPY', $srcUri, $headers);
        return $this->httpClient->sendRequest($request);
    }

    /**
     * Rename an item on the WebDAV server.
     *
     * @param string $srcPath  The source path of an item.
     * @param string $destPath The destination path of an item.
     * @return ResponseInterface Returns a Response class object.
     */
    public function move(string $srcPath, string $destPath): ResponseInterface
    {
        $srcUri = \is_null($this->parameters->getBaseAddress())
            ? new Uri($srcPath)
            : UriResolver::resolve(new Uri($srcPath), $this->parameters->getBaseAddress());
        $destUri = \is_null($this->parameters->getBaseAddress())
            ? new Uri($destPath)
            : UriResolver::resolve(new Uri($destPath), $this->parameters->getBaseAddress());

        $headers['Destination'] = (string) $destUri;
        $request = new Request('MOVE', $srcUri, $headers);
        return $this->httpClient->sendRequest($request);
    }

    /**
     * Make a directory on the WebDAV server.
     *
     * @param string $path The directory path.
     * @return ResponseInterface Returns a Response class object.
     */
    public function makeDirectory(string $path): ResponseInterface
    {
        $uri = \is_null($this->parameters->getBaseAddress())
            ? new Uri($path)
            : UriResolver::resolve(new Uri($path), $this->parameters->getBaseAddress());

        $request = new Request('MKCOL', $uri);
        return $this->httpClient->sendRequest($request);
    }

    /**
     * Check the existence of an item on the WebDAV server.
     *
     * @param string $path The path of an item.
     * @return bool Returns true if an item exists.
     */
    public function exists(string $path): bool
    {
        $uri = \is_null($this->parameters->getBaseAddress())
            ? new Uri($path)
            : UriResolver::resolve(new Uri($path), $this->parameters->getBaseAddress());

        $request = new Request('HEAD', $uri);
        $response = $this->httpClient->sendRequest($request);

        return $response->getStatusCode() >= 200 && $response->getStatusCode() < 300;
    }

    /**
     * List contents of a directory on the WebDAV server.
     *
     * @param string $path  The directory path.
     * @return list<string> Returns a list of contents of the directory.
     */
    public function list(string $path): array
    {
        $uri = \is_null($this->parameters->getBaseAddress())
            ? new Uri($path)
            : UriResolver::resolve(new Uri($path), $this->parameters->getBaseAddress());

        $headers = (new ListParameters())->getHeaders()->toArray();
        $request = new Request('PROPFIND', $uri, $headers);
        $response = $this->httpClient->sendRequest($request);

        if ($response->getStatusCode() < 200 || $response->getStatusCode() > 300) {
            return [];
        }

        $xml = \simplexml_load_string($response->getBody()->getContents(), SimpleXMLElement::class, 0, 'D', true);

        if ($xml === false) {
            return [];
        }

        $list = [];
        foreach ($xml->response as $element) {
            $list[] = (string) $element->href;
        }

        return $list;
    }
}
