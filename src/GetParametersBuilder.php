<?php

declare(strict_types=1);

namespace Ngmy\L4Dav;

class GetParametersBuilder
{
    /** @var string */
    private $destPath;

    public function setDestPath(string $destPath): self
    {
        $this->destPath = $destPath;
        return $this;
    }

    /**
     * Build WebDAV client options.
     */
    public function build(): GetParameters
    {
        return new GetParameters(
            $this->destPath
        );
    }
}
