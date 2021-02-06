<?php

declare(strict_types=1);

namespace Ngmy\L4Dav;

class PutParametersBuilder
{
    /** @var string */
    private $srcPath;

    /**
     * @return $this The value of the calling object
     */
    public function setSrcPath(string $srcPath): self
    {
        $this->srcPath = $srcPath;
        return $this;
    }

    /**
     * Build a new instance of a parameter class for the WebDAV PUT method.
     *
     * @return PutParameters A new instance of a parameter class for the WebDAV PUT method
     */
    public function build(): PutParameters
    {
        return new PutParameters($this->srcPath);
    }
}
