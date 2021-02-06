<?php

declare(strict_types=1);

namespace Ngmy\L4Dav;

use Psr\Http\Message\UriInterface;

class MoveParameters
{
    /** @var UriInterface */
    private $destUrl;

    /**
     * @param UriInterface $destUrl The destination path of a file
     */
    public function __construct(UriInterface $destUrl)
    {
        $this->destUrl = $destUrl;
    }

    public function destUrl(): UriInterface
    {
        return $this->destUrl;
    }
}
