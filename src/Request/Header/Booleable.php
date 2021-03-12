<?php

declare(strict_types=1);

namespace Ngmy\WebDav\Request\Header;

use InvalidArgumentException;

trait Booleable
{
    /**
     * @var bool
     * @enum
     */
    private static $T = true;
    /**
     * @var bool
     * @enum
     */
    private static $F = false;

    public static function getInstance(bool $value): self
    {
        foreach (self::values() as $enum) {
            if ($enum->getValue() == $value) {
                return $enum;
            }
        }
        throw new InvalidArgumentException('The value "%s" is invalid.');
    }

    public function getValue(): bool
    {
        return self::${$this->name()};
    }
}
