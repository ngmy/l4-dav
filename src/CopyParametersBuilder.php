<?php

declare(strict_types=1);

namespace Ngmy\L4Dav;

use Http\Discovery\Psr17FactoryDiscovery;
use Psr\Http\Message\UriInterface;

class CopyParametersBuilder
{
    /** @var UriInterface */
    private $destUrl;
    /** @var Overwrite */
    private $overwrite;

    /**
     * @param string|UriInterface $destUrl
     * @return $this The value of the calling object
     */
    public function setDestUrl($destUrl): self
    {
        $this->destUrl = Psr17FactoryDiscovery::findUriFactory()->createUri((string) $destUrl);
        return $this;
    }

    /**
     * @return $this The value of the calling object
     */
    public function setOverwrite(bool $overwrite): self
    {
        $this->overwrite = new Overwrite($overwrite);
        return $this;
    }

    /**
     * Build a new instance of a parameter class for the WebDAV COPY method.
     *
     * @return CopyParameters A new instance of a parameter class for the WebDAV COPY method
     */
    public function build(): CopyParameters
    {
        return new CopyParameters($this->destUrl, $this->overwrite);
    }
}
