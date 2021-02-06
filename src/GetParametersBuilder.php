<?php

declare(strict_types=1);

namespace Ngmy\L4Dav;

class GetParametersBuilder
{
    /** @var string */
    private $destPath;

    /**
     * @return $this The value of the calling object
     */
    public function setDestPath(string $destPath): self
    {
        $this->destPath = $destPath;
        return $this;
    }

    /**
     * Build a new instance of a parameter class for the WebDAV GET method.
     *
     * @return GetParameters A new instance of a parameter class for the WebDAV GET method
     */
    public function build(): GetParameters
    {
        return new GetParameters($this->destPath);
    }
}
