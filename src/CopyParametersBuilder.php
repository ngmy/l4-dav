<?php

declare(strict_types=1);

namespace Ngmy\L4Dav;

use Http\Discovery\Psr17FactoryDiscovery;
use Psr\Http\Message\UriInterface;

class CopyParametersBuilder
{
    /**
     * The destination resource URL.
     *
     * @var UriInterface
     */
    private $destUrl;
    /**
     * Whether to overwrite the resource if it exists.
     *
     * @var Overwrite
     */
    private $overwrite;

    /**
     * Set the destination resource URL.
     *
     * @param string|UriInterface $destUrl The destination resource URL
     * @return $this The value of the calling object
     */
    public function setDestUrl($destUrl): self
    {
        $this->destUrl = Psr17FactoryDiscovery::findUriFactory()->createUri((string) $destUrl);
        return $this;
    }

    /**
     * Set whether to overwrite the resource if it exists.
     *
     * @param bool $overwrite Whether to overwrite the resource if it exists
     * @return $this The value of the calling object
     */
    public function setOverwrite(bool $overwrite): self
    {
        $this->overwrite = new Overwrite($overwrite);
        return $this;
    }

    /**
     * Build a new instance of a parameters class for the WebDAV COPY operation.
     *
     * @return CopyParameters A new instance of a parameter class for the WebDAV COPY operation
     */
    public function build(): CopyParameters
    {
        return new CopyParameters($this->destUrl, $this->overwrite);
    }
}
