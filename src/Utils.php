<?php

declare(strict_types=1);

namespace Ngmy\L4Dav;

use DOMDocument;
use Psr\Http\Message\ResponseInterface;
use RuntimeException;

class Utils
{
    public static function resourceExists(ResponseInterface $response): bool
    {
        return $response->getStatusCode() >= 200
            && $response->getStatusCode() < 300;
    }

    /**
     * Write a response body to the specified file.
     *
     * @throws RuntimeException
     */
    public static function writeResponseBodyToFile(
        ResponseInterface $response,
        string $filePath,
        int $streamLength = 2048
    ): void {
        $fh = \fopen($filePath, 'x');
        if ($fh === false) {
            throw new RuntimeException('Failed to create file (' . $filePath . ')');
        }
        $stream = $response->getBody();
        while (!$stream->eof()) {
            \fwrite($fh, $stream->read($streamLength));
        }
        \fclose($fh);
    }

    public static function getResponseBodyAsXml(ResponseInterface $response): DOMDocument
    {
        return (new XmlResponseBodyParser($response))->parse();
    }
}
