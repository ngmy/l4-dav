<?php

declare(strict_types=1);

namespace Ngmy\L4Dav;

class PutParametersBuilder
{
    /** @var string */
    private $srcPath;

    public function setSrcPath(string $srcPath): self
    {
        $this->srcPath = $srcPath;
        return $this;
    }

    /**
     * Build WebDAV client options.
     */
    public function build(): PutParameters
    {
        return new PutParameters(
            $this->srcPath
        );
    }
}
