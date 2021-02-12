<?php

declare(strict_types=1);

namespace Ngmy\PhpWebDav;

use InvalidArgumentException;

class WebDavBool
{
    private const ENUM_TRUE = 'T';
    private const ENUM_FALSE = 'F';

    /** @var string */
    private $bool;

    public static function createFromBool(bool $bool): self
    {
        return new self($bool ? self::ENUM_TRUE : self::ENUM_FALSE);
    }

    public function __construct(string $bool)
    {
        $this->bool = $bool;
        $this->validate();
    }

    public function __toString(): string
    {
        return $this->bool;
    }

    private function validate(): void
    {
        if (
            !\in_array($this->bool, [
                self::ENUM_TRUE,
                self::ENUM_FALSE,
            ], true)
        ) {
            throw new InvalidArgumentException(
                \sprintf('The WebDAV bool must be "T" or "F", "%s" given.', $this->bool)
            );
        }
    }
}
