<?php

declare(strict_types=1);

namespace Ngmy\PhpWebDav;

class WebDavBool
{
    private const TRUE = 'T';
    private const FALSE = 'F';

    /** @var bool */
    private $bool;

    public function __construct(bool $bool)
    {
        $this->bool = $bool;
    }

    public function __toString(): string
    {
        return $this->bool ? self::TRUE : self::FALSE;
    }
}
