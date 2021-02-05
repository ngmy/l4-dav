<?php

declare(strict_types=1);

namespace Ngmy\L4Dav;

class GetParameters
{
    /** @var string */
    private $destPath;

    /**
     * @param string $destPath The destination path of a file
     */
    public function __construct(string $destPath)
    {
        $this->destPath = $destPath;
    }

    public function destPath(): string
    {
        return $this->destPath;
    }
}
