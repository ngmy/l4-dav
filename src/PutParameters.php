<?php

declare(strict_types=1);

namespace Ngmy\PhpWebDav;

class PutParameters
{
    /** @var string */
    private $sourcePath;

    /**
     * @param string $sourcePath The source path of a file
     */
    public function __construct(string $sourcePath)
    {
        $this->sourcePath = $sourcePath;
    }

    public function getSourcePath(): string
    {
        return $this->sourcePath;
    }
}
