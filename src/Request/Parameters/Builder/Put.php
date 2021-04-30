<?php

declare(strict_types=1);

namespace Ngmy\WebDav\Request\Parameters\Builder;

use InvalidArgumentException;
use Ngmy\WebDav\Request;

use function is_null;

class Put
{
    /**
     * The source file path.
     *
     * @var string|null
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
        if (is_null($this->sourcePath)) {
            throw new InvalidArgumentException();
        }
        return new Request\Parameters\Put($this->sourcePath);
    }
}
