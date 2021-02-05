<?php

declare(strict_types=1);

namespace Ngmy\L4Dav;

use Psr\Http\Message\UriInterface;

class CopyParameters
{
    /** @var UriInterface */
    private $destUri;
    /** @var bool */
    private $overwrite;

    /**
     * @param UriInterface $destUri The destination path of a file
     * @param bool                $overwrite Whether to overwrite copy
     */
    public function __construct(UriInterface $destUri, bool $overwrite = false)
    {
        $this->destUri = $destUri;
        $this->overwrite = $overwrite;
    }

    public function destUri(): UriInterface
    {
        return $this->destUri;
    }

    public function overwrite(): bool
    {
        return $this->overwrite;
    }
}
