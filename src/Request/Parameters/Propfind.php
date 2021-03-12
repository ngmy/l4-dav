<?php

declare(strict_types=1);

namespace Ngmy\WebDav\Request\Parameters;

use Ngmy\WebDav\Request;

class Propfind
{
    /** @var Request\Header\Depth */
    private $depth;

    public function __construct(?Request\Header\Depth $depth = null)
    {
        $this->depth = $depth ?: Request\Header\Depth::INFINITY();
    }

    public function getDepth(): Request\Header\Depth
    {
        return $this->depth;
    }
}
