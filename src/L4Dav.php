<?php

declare(strict_types=1);

namespace Ngmy\L4Dav;

use InvalidArgumentException;
use RuntimeException;
use SimpleXMLElement;

class L4Dav
{
    /** @var string The schema of the WebDAV server. */
    private $schema;
    /** @var string The hostname of the WebDAV server. */
    private $host;
    /** @var int The port of the WebDAV server. */
    private $port;
    /** @var string The path of the WebDAV server. */
    private $path;
    /** @var string The URL of the WebDAV server. */
    private $url;
    /** @var Request */
    private $request;

    /**
     * Create a new L4Dav class object.
     *
     * @param Request $request
     * @param string  $webDavUrl  The URL of the WebDAV server.
     * @param int     $webDavPort The port of the WebDAV server.
     * @throws InvalidArgumentException
     * @return void
     */
    public function __construct(Request $request, string $webDavUrl, int $webDavPort = 80)
    {
        if (!preg_match('/([a-z]+):\/\/([a-zA-Z0-9\.]+)(:[0-9]+){0,1}(.*)/', $webDavUrl, $m)) {
            throw new InvalidArgumentException('Invalid URL format (' . $webDavUrl . ')');
        }

        $this->schema = $m[1];
        $this->host   = $m[2];
        $this->port   = isset($m[3]) ? (int) ltrim($m[3], ':') : $webDavPort;
        $this->path   = isset($m[4]) ? rtrim($m[4], '/') . '/' : '/';

        $this->url = $this->schema . '://' . $this->host . $this->path;

        $this->request = $request;
    }

    /**
     * Download a file from the WebDAV server.
     *
     * @param string $srcPath  The source path of a file.
     * @param string $destPath The destination path of a file.
     * @throws RuntimeException
     * @return Response Returns a Response class object.
     */
    public function get(string $srcPath, string $destPath): Response
    {
        $fh = fopen($destPath, 'w');

        if ($fh === false) {
            throw new RuntimeException('Failed to open file (' . $destPath . ')');
        }

        $options = [
            CURLOPT_PORT           => $this->port,
            CURLOPT_FILE           => $fh,
            CURLOPT_RETURNTRANSFER => true,
        ];

        $result = $this->request->method('GET')
            ->url($this->url . $srcPath)
            ->options($options)
            ->send();

        fclose($fh);

        return $result;
    }

    /**
     * Upload a file to the WebDAV server.
     *
     * @param string $srcPath  The source path of a file.
     * @param string $destPath The destination path of a file.
     * @throws RuntimeException
     * @return Response Returns a Response class object.
     */
    public function put(string $srcPath, string $destPath): Response
    {
        $filesize = filesize($srcPath);
        $fh = fopen($srcPath, 'r');

        if ($fh === false) {
            throw new RuntimeException('Failed to open file (' . $srcPath . ')');
        }

        $options = [
            CURLOPT_PORT       => $this->port,
            CURLOPT_PUT        => true,
            CURLOPT_INFILE     => $fh,
            CURLOPT_INFILESIZE => $filesize,
        ];

        $result = $this->request->method('PUT')
            ->url($this->url . $destPath)
            ->options($options)
            ->send();

        fclose($fh);

        return $result;
    }

    /**
     * Delete an item on the WebDAV server.
     *
     * @param string $path The path of an item.
     * @return Response Returns a Response class object.
     */
    public function delete(string $path): Response
    {
        $options = [CURLOPT_PORT => $this->port];

        return $this->request->method('DELETE')
            ->url($this->url . $path)
            ->options($options)
            ->send();
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
        $options = [CURLOPT_PORT => $this->port];
        $headers = ['Destination' => $this->url . $destPath];

        return $this->request->method('COPY')
            ->url($this->url . $srcPath)
            ->headers($headers)
            ->options($options)
            ->send();
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
        $options = [CURLOPT_PORT => $this->port];
        $headers = ['Destination' => $this->url . $destPath];

        return $this->request->method('MOVE')
            ->url($this->url . $srcPath)
            ->headers($headers)
            ->options($options)
            ->send();
    }

    /**
     * Make a directory on the WebDAV server.
     *
     * @param string $path The directory path.
     * @return Response Returns a Response class object.
     */
    public function mkdir(string $path): Response
    {
        $options = [CURLOPT_PORT => $this->port];

        return $this->request->method('MKCOL')
            ->url($this->url . $path)
            ->options($options)
            ->send();
    }

    /**
     * Check the existence of an item on the WebDAV server.
     *
     * @param string $path The path of an item.
     * @return bool Returns true if an item exists.
     */
    public function exists(string $path): bool
    {
        $options = [
            CURLOPT_PORT           => $this->port,
            CURLOPT_NOBODY         => true,
            CURLOPT_RETURNTRANSFER => true,
        ];

        $response = $this->request->method('GET')
            ->url($this->url . $path)
            ->options($options)
            ->send();

        if ($response->getStatus() < 200 || $response->getStatus() > 300) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * List contents of a directory on the WebDAV server.
     *
     * @param string $path The directory path.
     * @return list<string> Returns a list of contents of the directory.
     */
    public function ls(string $path): array
    {
        $options = [CURLOPT_PORT => $this->port];
        $headers = ['Depth' => '1'];

        $response = $this->request->method('PROPFIND')
            ->url($this->url . $path)
            ->headers($headers)
            ->options($options)
            ->send();

        if ($response->getStatus() < 200 || $response->getStatus() > 300) {
            return [];
        } else {
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
}
