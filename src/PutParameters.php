<?php

declare(strict_types=1);

namespace Ngmy\L4Dav;

class PutParameters
{
    /** @var string */
    private $srcPath;

    /**
     * @param string $srcPath The source path of a file
     */
    public function __construct(string $srcPath)
    {
        $this->srcPath = $srcPath;
    }

    public function srcPath(): string
    {
        return $this->srcPath;
    }
}
