<?php

declare(strict_types=1);

namespace Ngmy\WebDav\Request\Header;

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
        return $value ? self::T() : self::F();
    }
}
