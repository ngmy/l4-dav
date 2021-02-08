<?php

declare(strict_types=1);

namespace Ngmy\L4Dav;

use DOMDocument;
use RuntimeException;

trait WebDavResponseHelpers
{
    public function resourceExists(): bool
    {
        return $this->response->getStatusCode() >= 200
            && $this->response->getStatusCode() < 300;
    }

    /**
     * Write a response body to the specified file.
     *
     * @throws RuntimeException
     */
    public function writeBodyToFile(string $filePath, int $streamLength = 2048): void
    {
        $fh = \fopen($filePath, 'x');
        if ($fh === false) {
            throw new RuntimeException('Failed to create file (' . $filePath . ')');
        }
        $stream = $this->response->getBody();
        while (!$stream->eof()) {
            \fwrite($fh, $stream->read($streamLength));
        }
        \fclose($fh);
    }

    public function getBodyAsXml(): DOMDocument
    {
        return (new XmlResponseBodyParser($this->response))->parse();
    }
}
