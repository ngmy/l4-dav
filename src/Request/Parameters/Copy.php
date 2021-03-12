<?php

declare(strict_types=1);

namespace Ngmy\WebDav\Request\Parameters;

use Ngmy\WebDav\Request;
use Psr\Http\Message\UriInterface;

class Copy
{
    /** @var UriInterface */
    private $destinationUrl;
    /** @var Request\Header\Overwrite */
    private $overwrite;

    /**
     * @param UriInterface             $destinationUrl The destination path of a file
     * @param Request\Header\Overwrite $overwrite      Whether to overwrite copy
     */
    public function __construct(UriInterface $destinationUrl, Request\Header\Overwrite $overwrite = null)
    {
        $this->destinationUrl = $destinationUrl;
        $this->overwrite = $overwrite ?: Request\Header\Overwrite::getInstance(false);
    }

    public function getDestinationUrl(): UriInterface
    {
        return $this->destinationUrl;
    }

    public function getOverwrite(): Request\Header\Overwrite
    {
        return $this->overwrite;
    }
}
