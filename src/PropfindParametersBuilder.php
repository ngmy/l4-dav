<?php

declare(strict_types=1);

namespace Ngmy\L4Dav;

class PropfindParametersBuilder
{
    /** @var Depth What depth to apply */
    private $depth;

    /**
     * Set what depth to apply.
     *
     * @param int|string $depth What depth to apply
     * @return $this The value of the calling object
     */
    public function setDepth($depth): self
    {
        $this->depth = new Depth($depth);
        return $this;
    }

    /**
     * Build a new instance of a parameter class for the WebDAV PROPFIND operation.
     *
     * @return PropfindParameters A new instance of a parameter class for the WebDAV PROPFIND operation
     */
    public function build(): PropfindParameters
    {
        return new PropfindParameters($this->depth);
    }
}
