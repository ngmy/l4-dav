<?php

declare(strict_types=1);

namespace Ngmy\PhpWebDav;

class PropfindParameters
{
    /** @var Depth */
    private $depth;

    public function __construct(?Depth $depth = null)
    {
        $this->depth = $depth ?: Depth::INFINITY();
    }

    public function getDepth(): Depth
    {
        return $this->depth;
    }
}
