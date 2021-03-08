<?php

declare(strict_types=1);

namespace Ngmy\PhpWebDav;

use Psr\Http\Message\UriInterface;

class CopyParameters
{
    /** @var UriInterface */
    private $destinationUrl;
    /** @var Overwrite */
    private $overwrite;

    /**
     * @param UriInterface $destinationUrl The destination path of a file
     * @param Overwrite    $overwrite      Whether to overwrite copy
     */
    public function __construct(UriInterface $destinationUrl, Overwrite $overwrite = null)
    {
        $this->destinationUrl = $destinationUrl;
        $this->overwrite = $overwrite ?: Overwrite::getType(false);
    }

    public function getDestinationUrl(): UriInterface
    {
        return $this->destinationUrl;
    }

    public function getOverwrite(): Overwrite
    {
        return $this->overwrite;
    }
}
