<?php

declare(strict_types=1);

namespace Ngmy\L4Dav;

use Http\Discovery\Psr17FactoryDiscovery;
use InvalidArgumentException;
use League\Uri\Contracts\PathInterface;
use League\Uri\Uri as UriManipulator;
use Psr\Http\Message\UriInterface;

class BaseUrl
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

    public function withPath(PathInterface $path): self
    {
        return new self((string) UriManipulator::createFromBaseUri($path, $this->uri));
    }

    public function __toString(): string
    {
        return (string) $this->uri;
    }

    private function validate(): void
    {
        if (empty($this->uri->getScheme())) {
            throw new InvalidArgumentException(\sprintf('The base URI `%s` must be absolute', $this->uri));
        }
        if (!empty($this->uri->getQuery())) {
            throw new InvalidArgumentException(\sprintf('The base URI `%s` can not contain query', $this->uri));
        }
        if (!empty($this->uri->getFragment())) {
            throw new InvalidArgumentException(\sprintf('The base URI `%s` can not contain fragment', $this->uri));
        }
    }
}
