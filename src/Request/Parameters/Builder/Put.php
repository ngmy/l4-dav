<?php

declare(strict_types=1);

namespace Ngmy\WebDav\Request\Parameters\Builder;

use Ngmy\WebDav\Request;

class Put
{
    /**
     * The source file path.
     *
     * @var string
     */
    private $sourcePath;

    /**
     * Set the source file path.
     *
     * @param string $sourcePath The source file path
     * @return $this The value of the calling object
     */
    public function setSourcePath(string $sourcePath): self
    {
        $this->sourcePath = $sourcePath;
        return $this;
    }

    /**
     * Build a new instance of a parameter class for the WebDAV PUT operation.
     *
     * @return Request\Parameters\Put A new instance of a parameter class for the WebDAV PUT operation
     */
    public function build(): Request\Parameters\Put
    {
        return new Request\Parameters\Put($this->sourcePath);
    }
}
