<?php

declare(strict_types=1);

namespace Ngmy\L4Dav;

class PutParametersBuilder
{
    /**
     * The source file path.
     *
     * @var string
     */
    private $srcPath;

    /**
     * Set the source file path.
     *
     * @param string $srcPath The source file path
     * @return $this The value of the calling object
     */
    public function setSrcPath(string $srcPath): self
    {
        $this->srcPath = $srcPath;
        return $this;
    }

    /**
     * Build a new instance of a parameter class for the WebDAV PUT operation.
     *
     * @return PutParameters A new instance of a parameter class for the WebDAV PUT operation
     */
    public function build(): PutParameters
    {
        return new PutParameters($this->srcPath);
    }
}
