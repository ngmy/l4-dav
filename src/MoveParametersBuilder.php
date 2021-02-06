<?php

declare(strict_types=1);

namespace Ngmy\L4Dav;

use Http\Discovery\Psr17FactoryDiscovery;
use Psr\Http\Message\UriInterface;

class MoveParametersBuilder
{
    /** @var UriInterface */
    private $destUrl;

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
     * Build a new instance of a parameter class for the WebDAV MOVE method.
     *
     * @return MoveParameters A new instance of a parameter class for the WebDAV MOVE method
     */
    public function build(): MoveParameters
    {
        return new MoveParameters($this->destUrl);
    }
}
