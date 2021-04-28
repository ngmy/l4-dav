<?php

declare(strict_types=1);

namespace Ngmy\WebDav\Request\Header;

use Ngmy\WebDav\Request;
use SplFileObject;

class ContentLength
{
    private const HEADER_NAME = 'Content-Length';

    /** @var int */
    private $contentLength;

    public static function createFromFilePath(string $filePath): self
    {
        $contentLength = (new SplFileObject($filePath))->getSize();
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

    public function provide(Request\Headers $headers): Request\Headers
    {
        return $headers->withHeader(self::HEADER_NAME, (string) $this->contentLength);
    }
}
