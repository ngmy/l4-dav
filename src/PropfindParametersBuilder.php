<?php

declare(strict_types=1);

namespace Ngmy\L4Dav;

class PropfindParametersBuilder
{
    /** @var Depth */
    private $depth;

    /**
     * @param int|string $depth
     */
    public function setDepth($depth): self
    {
        $this->depth = new Depth($depth);
        return $this;
    }

    /**
     * Build WebDAV client options.
     */
    public function build(): PropfindParameters
    {
        return new PropfindParameters(
            $this->depth
        );
    }
}
