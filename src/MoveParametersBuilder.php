<?php

declare(strict_types=1);

namespace Ngmy\L4Dav;

use Http\Discovery\Psr17FactoryDiscovery;
use Psr\Http\Message\UriInterface;

class MoveParametersBuilder
{
    /** @var UriInterface */
    private $destUri;

    /**
     * @param string|UriInterface $destUri
     */
    public function setDestUri($destUri): self
    {
        $this->destUri = Psr17FactoryDiscovery::findUriFactory()->createUri((string) $destUri);
        return $this;
    }

    /**
     * Build WebDAV client options.
     */
    public function build(): MoveParameters
    {
        return new MoveParameters(
            $this->destUri
        );
    }
}
