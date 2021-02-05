<?php

declare(strict_types=1);

namespace Ngmy\L4Dav;

use Psr\Http\Message\UriInterface;

class MoveParameters
{
    /** @var UriInterface */
    private $destUri;

    /**
     * @param UriInterface $destUri The destination path of a file
     */
    public function __construct(UriInterface $destUri)
    {
        $this->destUri = $destUri;
    }

    public function destUri(): UriInterface
    {
        return $this->destUri;
    }
}
