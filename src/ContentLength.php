<?php

declare(strict_types=1);

namespace Ngmy\PhpWebDav;

use RuntimeException;

class ContentLength implements WebDavHeaderInterface
{
    /** @var int */
    private $contentLength;

    /**
     * @throws RuntimeException
     */
    public static function createFromFilePath(string $filePath): self
    {
        $contentLength = filesize($filePath);
        if ($contentLength === false) {
            throw new RuntimeException();
        }
        return new self($contentLength);
    }

    public function __construct(int $contentLength)
    {
        $this->contentLength = $contentLength;
    }

    public function __toString(): string
    {
        return (string) $this->contentLength;
    }

    public function provide(Headers $headers): Headers
    {
        return $headers->withHeader('Content-Length', (string) $this->contentLength);
    }
}
