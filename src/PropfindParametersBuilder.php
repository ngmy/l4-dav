<?php

declare(strict_types=1);

namespace Ngmy\L4Dav;

class PropfindParametersBuilder
{
    /** @var Depth */
    private $depth;

    /**
     * @param int|string $depth
     * @return $this The value of the calling object
     */
    public function setDepth($depth): self
    {
        $this->depth = new Depth($depth);
        return $this;
    }

    /**
     * Build a new instance of a parameter class for the WebDAV PROPFIND method.
     *
     * @return PropfindParameters A new instance of a parameter class for the WebDAV PROPFIND method
     */
    public function build(): PropfindParameters
    {
        return new PropfindParameters($this->depth);
    }
}
