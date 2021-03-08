<?php

declare(strict_types=1);

namespace Ngmy\PhpWebDav;

use Ngmy\Enum\Enum;

/**
 * @method static self NONE()
 * @method static self BASIC()
 * @method static self DIGEST()
 */
class AuthType extends Enum
{
    /**
     * @var null
     * @enum
     */
    private static $NONE;
    /**
     * @var null
     * @enum
     */
    private static $BASIC;
    /**
     * @var null
     * @enum
     */
    private static $DIGEST;

    /**
     * @param array<int, mixed> $curlOptions
     * @return array<int, mixed>
     */
    public function provide(array $curlOptions): array
    {
        if ($this->name() == 'BASIC') {
            $curlOptions[\CURLOPT_HTTPAUTH] = \CURLAUTH_BASIC;
        }
        if ($this->name() == 'DIGEST') {
            $curlOptions[\CURLOPT_HTTPAUTH] = \CURLAUTH_DIGEST;
        }
        return $curlOptions;
    }
}
