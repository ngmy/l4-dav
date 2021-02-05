<?php

declare(strict_types=1);

namespace Ngmy\L4Dav;

use Psr\Http\Message\UriInterface;

class CopyParameters
{
    /** @var UriInterface */
    private $destUri;
    /** @var Overwrite */
    private $overwrite;

    /**
     * @param UriInterface $destUri The destination path of a file
     * @param Overwrite                $overwrite Whether to overwrite copy
     */
    public function __construct(UriInterface $destUri, Overwrite $overwrite = null)
    {
        $this->destUri = $destUri;
        $this->overwrite = $overwrite ?: new Overwrite(false);
    }

    public function destUri(): UriInterface
    {
        return $this->destUri;
    }

    public function overwrite(): Overwrite
    {
        return $this->overwrite;
    }
}
