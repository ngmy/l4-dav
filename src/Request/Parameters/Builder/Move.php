<?php

declare(strict_types=1);

namespace Ngmy\WebDav\Request\Parameters\Builder;

use Http\Discovery\Psr17FactoryDiscovery;
use Ngmy\WebDav\Request;
use Psr\Http\Message\UriInterface;

class Move
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
     * @return Request\Parameters\Move A new instance of a parameter class for the WebDAV MOVE operation
     */
    public function build(): Request\Parameters\Move
    {
        return new Request\Parameters\Move($this->destinationUrl);
    }
}
