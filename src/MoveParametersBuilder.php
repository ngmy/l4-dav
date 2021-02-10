<?php

declare(strict_types=1);

namespace Ngmy\PhpWebDav;

use Http\Discovery\Psr17FactoryDiscovery;
use Psr\Http\Message\UriInterface;

class MoveParametersBuilder
{
    /**
     * The destination resource URL.
     *
     * @var UriInterface
     */
    private $destinationUrl;

    /**
     * Set the destination resource URL.
     *
     * @param string|UriInterface $destinationUrl The destination resource URL
     * @return $this The value of the calling object
     */
    public function setDestinationUrl($destinationUrl): self
    {
        $this->destinationUrl = Psr17FactoryDiscovery::findUriFactory()->createUri((string) $destinationUrl);
        return $this;
    }

    /**
     * Build a new instance of a parameter class for the WebDAV MOVE operation.
     *
     * @return MoveParameters A new instance of a parameter class for the WebDAV MOVE operation
     */
    public function build(): MoveParameters
    {
        return new MoveParameters($this->destinationUrl);
    }
}
