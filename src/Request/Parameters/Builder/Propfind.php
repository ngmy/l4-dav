<?php

declare(strict_types=1);

namespace Ngmy\WebDav\Request\Parameters\Builder;

use Ngmy\WebDav\Request;

class Propfind
{
    /**
     * What depth to apply.
     *
     * @var Request\Header\Depth|null
     */
    private $depth;

    /**
     * Set what depth to apply.
     *
     * @param int|string $depth What depth to apply
     * @return $this The value of the calling object
     */
    public function setDepth($depth): self
    {
        $this->depth = Request\Header\Depth::getInstance((string) $depth);
        return $this;
    }

    /**
     * Build a new instance of a parameter class for the WebDAV PROPFIND operation.
     *
     * @return Request\Parameters\Propfind A new instance of a parameter class for the WebDAV PROPFIND operation
     */
    public function build(): Request\Parameters\Propfind
    {
        return new Request\Parameters\Propfind($this->depth);
    }
}
