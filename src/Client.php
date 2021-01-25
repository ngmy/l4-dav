<?php

declare(strict_types=1);

namespace Ngmy\L4Dav;

use RuntimeException;
use SimpleXMLElement;

class Client
{
    /** @var HttpClient */
    private $httpClient;
    /** @var Server */
    private $server;

    /**
     * Create a new Client class object.
     *
     * @param HttpClient $httpClient
     * @param Server     $server
     * @return void
     */
    public function __construct(HttpClient $httpClient, Server $server, ?Credential $credential = null)
    {
        $this->httpClient = $httpClient;
        $this->server = $server;
        $this->credential = $credential;
    }

    /**
     * Download a file from the WebDAV server.
     *
     * @param string $srcPath  The source path of a file.
     * @param string $destPath The destination path of a file.
     * @throws RuntimeException
     * @return Response Returns a Response class object.
     */
    public function download(string $srcPath, string $destPath): Response
    {
        $fh = fopen($destPath, 'w');

        if ($fh === false) {
            throw new RuntimeException('Failed to open file (' . $destPath . ')');
        }

        $options['curl'][CURLOPT_PORT] = $this->server->port();
        $options['curl'][CURLOPT_FILE] = $fh;
        $options['curl'][CURLOPT_RETURNTRANSFER] = true;

        if (!is_null($this->credential)) {
            $options['auth']['username'] = $this->credential->username();
            $options['auth']['password'] = $this->credential->password();
        }

        $response = $this->httpClient->request('GET', $this->server->url()->withPath($srcPath), $options);

        fclose($fh);

        return $response;
    }

    /**
     * Upload a file to the WebDAV server.
     *
     * @param string $srcPath  The source path of a file.
     * @param string $destPath The destination path of a file.
     * @throws RuntimeException
     * @return Response Returns a Response class object.
     */
    public function upload(string $srcPath, string $destPath): Response
    {
        $filesize = filesize($srcPath);
        $fh = fopen($srcPath, 'r');

        if ($fh === false) {
            throw new RuntimeException('Failed to open file (' . $srcPath . ')');
        }

        $options['curl'][CURLOPT_PORT] = $this->server->port();
        $options['curl'][CURLOPT_PUT] = true;
        $options['curl'][CURLOPT_INFILE] = $fh;
        $options['curl'][CURLOPT_INFILESIZE] = $filesize;

        if (!is_null($this->credential)) {
            $options['auth']['username'] = $this->credential->username();
            $options['auth']['password'] = $this->credential->password();
        }

        $response = $this->httpClient->request('PUT', $this->server->url()->withPath($destPath), $options);

        fclose($fh);

        return $response;
    }

    /**
     * Delete an item on the WebDAV server.
     *
     * @param string $path The path of an item.
     * @return Response Returns a Response class object.
     */
    public function delete(string $path): Response
    {
        $options['curl'][CURLOPT_PORT] = $this->server->port();

        if (!is_null($this->credential)) {
            $options['auth']['username'] = $this->credential->username();
            $options['auth']['password'] = $this->credential->password();
        }

        return $this->httpClient->request('DELETE', $this->server->url()->withPath($path), $options);
    }

    /**
     * Copy an item on the WebDAV server.
     *
     * @param string $srcPath  The source path of an item.
     * @param string $destPath The destination path of an item.
     * @return Response Returns a Response class object.
     */
    public function copy(string $srcPath, string $destPath): Response
    {
        $options['curl'][CURLOPT_PORT] = $this->server->port();
        $options['headers']['Destination'] = $this->server->url()->withPath($destPath)->value();

        if (!is_null($this->credential)) {
            $options['auth']['username'] = $this->credential->username();
            $options['auth']['password'] = $this->credential->password();
        }

        return $this->httpClient->request('COPY', $this->server->url()->withPath($srcPath), $options);
    }

    /**
     * Rename an item on the WebDAV server.
     *
     * @param string $srcPath  The source path of an item.
     * @param string $destPath The destination path of an item.
     * @return Response Returns a Response class object.
     */
    public function move(string $srcPath, string $destPath): Response
    {
        $options['curl'][CURLOPT_PORT] = $this->server->port();
        $options['headers']['Destination'] = $this->server->url()->withPath($destPath)->value();

        if (!is_null($this->credential)) {
            $options['auth']['username'] = $this->credential->username();
            $options['auth']['password'] = $this->credential->password();
        }

        return $this->httpClient->request('MOVE', $this->server->url()->withPath($srcPath), $options);
    }

    /**
     * Make a directory on the WebDAV server.
     *
     * @param string $path The directory path.
     * @return Response Returns a Response class object.
     */
    public function makeDirectory(string $path): Response
    {
        $options['curl'][CURLOPT_PORT] = $this->server->port();

        if (!is_null($this->credential)) {
            $options['auth']['username'] = $this->credential->username();
            $options['auth']['password'] = $this->credential->password();
        }

        return $this->httpClient->request('MKCOL', $this->server->url()->withPath($path), $options);
    }

    /**
     * Check the existence of an item on the WebDAV server.
     *
     * @param string $path The path of an item.
     * @return bool Returns true if an item exists.
     */
    public function exists(string $path): bool
    {
        $options['curl'][CURLOPT_PORT] = $this->server->port();
        $options['curl'][CURLOPT_NOBODY] = true;
        $options['curl'][CURLOPT_RETURNTRANSFER] = true;

        if (!is_null($this->credential)) {
            $options['auth']['username'] = $this->credential->username();
            $options['auth']['password'] = $this->credential->password();
        }

        $response = $this->httpClient->request('HEAD', $this->server->url()->withPath($path), $options);

        return $response->getStatus() >= 200 && $response->getStatus() < 300;
    }

    /**
     * List contents of a directory on the WebDAV server.
     *
     * @param string $path  The directory path.
     * @return list<string> Returns a list of contents of the directory.
     */
    public function list(string $path): array
    {
        $options['curl'][CURLOPT_PORT] = $this->server->port();
        $options['headers']['Depth'] = '1';

        if (!is_null($this->credential)) {
            $options['auth']['username'] = $this->credential->username();
            $options['auth']['password'] = $this->credential->password();
        }

        $response = $this->httpClient->request('PROPFIND', $this->server->url()->withPath($path), $options);

        if ($response->getStatus() < 200 || $response->getStatus() > 300) {
            return [];
        }

        $xml = simplexml_load_string($response->getBody(), SimpleXMLElement::class, 0, 'D', true);

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
