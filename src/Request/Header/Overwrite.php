<?php

declare(strict_types=1);

namespace Ngmy\WebDav\Request\Header;

use Ngmy\Enum\Enum;
use Ngmy\WebDav\Request;

/**
 * @method static self T()
 * @method static self F()
 */
class Overwrite extends Enum
{
    use Booleable;

    private const HEADER_NAME = 'Overwrite';

    public function provide(Request\Headers $headers): Request\Headers
    {
        return $headers->withHeader(self::HEADER_NAME, (string) $this);
    }
}
