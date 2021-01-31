<?php

declare(strict_types=1);

namespace Ngmy\L4Dav;

use Http\Discovery\Psr17FactoryDiscovery;
use InvalidArgumentException;
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

    /**
     * @param ShortcutUrl|string|UriInterface $shortcutUrl
     */
    public function uriWithShortcutUrl($shortcutUrl): UriInterface
    {
        $shortcutUrl = new ShortcutUrl((string) $shortcutUrl);

        $baseUrlPath = $this->uri->getPath();
        $shortcutUrlPath = $shortcutUrl->uri()->getPath();

        $baseUrlPathHasTrailingSlash = false;
        $shortcutUrlPathHasLeadingSlash = false;

        if ($baseUrlPath != '' && $baseUrlPath[-1] == '/') {
            $baseUrlPathHasTrailingSlash = true;
        }
        if ($shortcutUrlPath != '' && $shortcutUrlPath[0] == '/') {
            $shortcutUrlPathHasLeadingSlash = true;
        }

        if ($baseUrlPath == '' && $shortcutUrlPath == '') {
            $newPath = '';
        } elseif ($baseUrlPathHasTrailingSlash && $shortcutUrlPathHasLeadingSlash) {
            $newPath = \substr($baseUrlPath, 0, \strlen($baseUrlPath) - 1) . '/' . \substr($shortcutUrlPath, 1);
        } elseif (!$baseUrlPathHasTrailingSlash && !$shortcutUrlPathHasLeadingSlash) {
            $newPath = $baseUrlPath . '/' . $shortcutUrlPath;
        } else {
            $newPath = $baseUrlPath . $shortcutUrlPath;
        }

        return $this->uri
            ->withPath($newPath)
            ->withQuery($shortcutUrl->uri()->getQuery())
            ->withFragment($shortcutUrl->uri()->getFragment());
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
                \sprintf('The base URL `%s` is invalid : scheme must be http or https', $this->uri)
            );
        }
        if (empty($this->uri->getAuthority())) {
            throw new InvalidArgumentException(
                \sprintf('The base URL `%s` must contain authority', $this->uri)
            );
        }
        if ($this->uri->getPath() != '' && $this->uri->getPath()[0] != '/') {
            throw new InvalidArgumentException(
                \sprintf('The base URL `%s` is invalid : path must be empty or begin with a slash', $this->uri)
            );
        }
        if (!empty($this->uri->getQuery())) {
            throw new InvalidArgumentException(
                \sprintf('The base URL `%s` must not contain query', $this->uri)
            );
        }
        if (!empty($this->uri->getFragment())) {
            throw new InvalidArgumentException(
                \sprintf('The base URL `%s` must not contain fragment', $this->uri)
            );
        }
    }
}
