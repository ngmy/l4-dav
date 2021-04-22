<?php

declare(strict_types=1);

namespace Ngmy\WebDav\Request;

use InvalidArgumentException;

use function in_array;
use function sprintf;

class Method
{
    private const GET = 'GET';
    private const PUT = 'PUT';
    private const DELETE  = 'DELETE';
    private const HEAD = 'HEAD';
    private const COPY = 'COPY';
    private const MOVE = 'MOVE';
    private const MKCOL = 'MKCOL';
    private const PROPFIND = 'PROPFIND';
    private const PROPPATCH = 'PROPPATCH';

    /** @var string */
    private $method;

    public static function createGetMethod(): self
    {
        return new self(self::GET);
    }

    public static function createPutMethod(): self
    {
        return new self(self::PUT);
    }

    public static function createDeleteMethod(): self
    {
        return new self(self::DELETE);
    }

    public static function createHeadMethod(): self
    {
        return new self(self::HEAD);
    }

    public static function createCopyMethod(): self
    {
        return new self(self::COPY);
    }

    public static function createMoveMethod(): self
    {
        return new self(self::MOVE);
    }

    public static function createMkcolMethod(): self
    {
        return new self(self::MKCOL);
    }

    public static function createPropfindMethod(): self
    {
        return new self(self::PROPFIND);
    }

    public static function createProppatchMethod(): self
    {
        return new self(self::PROPPATCH);
    }

    public function __toString(): string
    {
        return $this->method;
    }

    private function __construct(string $method)
    {
        $this->method = $method;
        $this->validate();
    }

    /**
     * @throws InvalidArgumentException
     */
    private function validate(): void
    {
        if (
            !in_array($this->method, [
                self::GET,
                self::PUT,
                self::DELETE,
                self::HEAD,
                self::COPY,
                self::MOVE,
                self::MKCOL,
                self::PROPFIND,
                self::PROPPATCH,
            ])
        ) {
            throw new InvalidArgumentException(
                sprintf('The WebDAV method "%s" is not allowed.', $this->method)
            );
        };
    }
}
