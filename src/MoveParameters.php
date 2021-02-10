<?php

declare(strict_types=1);

namespace Ngmy\PhpWebDav;

use Psr\Http\Message\UriInterface;

class MoveParameters
{
    /** @var UriInterface */
    private $destinationUrl;

    /**
     * @param UriInterface $destinationUrl The destination path of a file
     */
    public function __construct(UriInterface $destinationUrl)
    {
        $this->destinationUrl = $destinationUrl;
    }

    public function getDestinationUrl(): UriInterface
    {
        return $this->destinationUrl;
    }
}
