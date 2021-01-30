<?php

declare(strict_types=1);

namespace Ngmy\L4Dav;

use Http\Discovery\Psr17FactoryDiscovery;
use InvalidArgumentException;
use Psr\Http\Message\UriInterface;

class AbsoluteUri
{
    /** @var UriInterface */
    private $uri;

    /**
     * @param string|UriInterface $uri
     */
    public function __construct($uri)
    {
        $this->uri = Psr17FactoryDiscovery::findUriFactory()->createUri((string) $uri);
        $this->validate();
    }

    public function uri(): UriInterface
    {
        return $this->uri;
    }

    public function __toString(): string
    {
        return (string) $this->uri;
    }

    private function validate(): void
    {
        if (empty($this->uri->getScheme())) {
            throw new InvalidArgumentException(\sprintf('The absolute URI `%s` must be absolute', $this->uri));
        }
    }
}
