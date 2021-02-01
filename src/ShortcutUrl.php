<?php

declare(strict_types=1);

namespace Ngmy\L4Dav;

use Http\Discovery\Psr17FactoryDiscovery;
use InvalidArgumentException;
use Psr\Http\Message\UriInterface;

class ShortcutUrl
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

    public function hasPath(): bool
    {
        return $this->uri->getPath() != '';
    }

    public function hasLeadingSlash(): bool
    {
        return $this->uri->getPath() != '' && $this->uri->getPath()[0] == '/';
    }

    public function __toString(): string
    {
        return (string) $this->uri;
    }

    /**
     * @throws InvalidArgumentException
     */
    private function validate(): void
    {
        if (!empty($this->uri->getScheme())) {
            throw new InvalidArgumentException(
                \sprintf('The shortcut URL `%s` must not contain scheme', $this->uri)
            );
        }
        if (!empty($this->uri->getAuthority())) {
            throw new InvalidArgumentException(
                \sprintf('The shortcut URL `%s` must not contain authority', $this->uri)
            );
        }
    }
}
