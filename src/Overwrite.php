<?php

declare(strict_types=1);

namespace Ngmy\PhpWebDav;

class Overwrite
{
    /** @var bool */
    private $overwrite;

    public function __construct(bool $overwrite)
    {
        $this->overwrite = $overwrite;
    }

    public function __toString(): string
    {
        return $this->overwrite ? 'T' : 'F';
    }
}
