<?php

declare(strict_types=1);

namespace Ngmy\L4Dav;

use Psr\Http\Message\UriInterface;

class CopyParameters
{
    /** @var UriInterface */
    private $destUrl;
    /** @var Overwrite */
    private $overwrite;

    /**
     * @param UriInterface $destUrl   The destination path of a file
     * @param Overwrite    $overwrite Whether to overwrite copy
     */
    public function __construct(UriInterface $destUrl, Overwrite $overwrite = null)
    {
        $this->destUrl = $destUrl;
        $this->overwrite = $overwrite ?: new Overwrite(false);
    }

    public function destUrl(): UriInterface
    {
        return $this->destUrl;
    }

    public function overwrite(): Overwrite
    {
        return $this->overwrite;
    }
}
