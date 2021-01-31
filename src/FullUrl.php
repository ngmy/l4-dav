<?php

declare(strict_types=1);

namespace Ngmy\L4Dav;

use Http\Discovery\Psr17FactoryDiscovery;
use InvalidArgumentException;
use Psr\Http\Message\UriInterface;

class FullUrl
{
    /** @var UriInterface */
    private $uri;

    /**
     * @param string|UriInterface $uri
     * @throws InvalidArgumentException
     */
    public static function createFromBaseUrl($uri, ?BaseUrl $baseUrl = null): self
    {
        $uri = Psr17FactoryDiscovery::findUriFactory()->createUri((string) $uri);

        if (\is_null($baseUrl)) {
            return new self($uri);
        }

        try {
            $uri = new ShortcutUrl($uri);
        } catch (InvalidArgumentException $e) {
            \assert($uri instanceof UriInterface);
            return new self($uri);
        }

        $uri = $baseUrl->uriWithShortcutUrl($uri);
        return new self($uri);
    }

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

    /**
     * @throws InvalidArgumentException
     */
    private function validate(): void
    {
        if (!\in_array($this->uri->getScheme(), ['http', 'https'])) {
            throw new InvalidArgumentException(
                \sprintf('The full URL `%s` is invalid : scheme must be http or https', $this->uri)
            );
        }
        if (empty($this->uri->getAuthority())) {
            throw new InvalidArgumentException(
                \sprintf('The full URL `%s` must contain authority', $this->uri)
            );
        }
        if ($this->uri->getPath() != '' && $this->uri->getPath()[0] != '/') {
            throw new InvalidArgumentException(
                \sprintf('The full URL `%s` is invalid : path must be empty or begin with a slash', $this->uri)
            );
        }
    }
}
