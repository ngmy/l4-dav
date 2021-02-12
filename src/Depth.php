<?php

declare(strict_types=1);

namespace Ngmy\PhpWebDav;

use InvalidArgumentException;

class Depth implements WebDavHeaderInterface
{
    private const HEADER_NAME = 'Depth';
    private const ENUM_ZERO = '0';
    private const ENUM_ONE = '1';
    private const ENUM_INFINITY = 'infinity';

    /** @var string */
    private $depth;

    /**
     * @param int|string $depth
     */
    public function __construct($depth = null)
    {
        $this->depth = \is_null($depth) ? self::ENUM_INFINITY : \strtolower((string) $depth);
        $this->validate();
    }

    public function __toString(): string
    {
        return $this->depth;
    }

    public function provide(Headers $headers): Headers
    {
        return $headers->withHeader(self::HEADER_NAME, (string) $this->depth);
    }

    private function validate(): void
    {
        if (
            !\in_array($this->depth, [
                self::ENUM_ZERO,
                self::ENUM_ONE,
                self::ENUM_INFINITY,
            ], true)
        ) {
            throw new InvalidArgumentException(
                \sprintf('The depth must be "0", "1" or "infinity", "%s" given.', $this->depth)
            );
        }
    }
}
