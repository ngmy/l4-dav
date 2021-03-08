<?php

declare(strict_types=1);

namespace Ngmy\PhpWebDav;

use InvalidArgumentException;
use Ngmy\Enum\Enum;

/**
 * @method static self T()
 * @method static self F()
 */
class WebDavBool extends Enum
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
