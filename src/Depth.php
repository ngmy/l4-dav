<?php

declare(strict_types=1);

namespace Ngmy\PhpWebDav;

use InvalidArgumentException;
use Ngmy\Enum\Enum;

/**
 * @method static self ZERO()
 * @method static self ONE()
 * @method static self INFINITY()
 */
class Depth extends Enum
{
    private const HEADER_NAME = 'Depth';

    /**
     * @var string
     * @enum
     */
    private static $ZERO = '0';
    /**
     * @var string
     * @enum
     */
    private static $ONE = '1';
    /**
     * @var string
     * @enum
     */
    private static $INFINITY = 'infinity';

    public static function getInstance(string $value): self
    {
        foreach (self::values() as $enum) {
            if ($enum->getValue() == $value) {
                return $enum;
            }
        }
        throw new InvalidArgumentException('The value "%s" is invalid.');
    }

    public function getValue(): string
    {
        return self::${$this->name()};
    }

    public function provide(Headers $headers): Headers
    {
        return $headers->withHeader(self::HEADER_NAME, self::${$this->name()});
    }
}
