<?php

declare(strict_types=1);

namespace Ngmy\L4Dav;

use Http\Discovery\Psr17FactoryDiscovery;
use InvalidArgumentException;
use Psr\Http\Message\UriInterface;

abstract class Url
{
    /** @var UriInterface */
    protected $uri;

    /**
     * @throws InvalidArgumentException
     */
    abstract protected function validate(): void;

    /**
     * @param string|UriInterface|Url $uri
     */
    public static function createBaseUrl($uri): BaseUrl
    {
        return new BaseUrl($uri);
    }

    /**
     * @param string|UriInterface|Url $uri
     */
    public static function createShortcutUrl($uri): ShortcutUrl
    {
        return new ShortcutUrl($uri);
    }

    /**
     * @param string|UriInterface|Url $uri
     * @throws InvalidArgumentException
     */
    public static function createFullUrl($uri, ?BaseUrl $baseUrl = null): FullUrl
    {
        $candidate = new CandidateUrl(Psr17FactoryDiscovery::findUriFactory()->createUri((string) $uri));

        if ($candidate->isFullUrl()) {
            return new FullUrl($candidate);
        }

        if ($candidate->isShortcutUrl() && !\is_null($baseUrl)) {
            $candidate = $baseUrl->uriWithShortcutUrl(self::createShortcutUrl($candidate));
            return new FullUrl($candidate);
        }

        throw new InvalidArgumentException(
            \sprintf('The URL `%s` is invalid : The URL must be full URL, or be shortcut and base URL pairs', $uri)
        );
    }

    public function uri(): UriInterface
    {
        return $this->uri;
    }

    public function hasPath(): bool
    {
        return $this->uri->getPath() != '';
    }

    public function hasPathWithTrailingSlash(): bool
    {
        return $this->hasPath() && $this->uri->getPath()[-1] == '/';
    }

    public function hasPathWithLeadingSlash(): bool
    {
        return $this->hasPath() && $this->uri->getPath()[0] == '/';
    }

    public function __toString(): string
    {
        return (string) $this->uri;
    }

    /**
     * @param string|UriInterface|Url $uri
     */
    private function __construct($uri)
    {
        $this->uri = Psr17FactoryDiscovery::findUriFactory()->createUri((string) $uri);
        $this->validate();
    }

    private function isBaseUrl(): bool
    {
        return $this->isFullUrl()
            && empty($this->uri->getQuery())
            && empty($this->uri->getFragment());
    }

    private function isShortcutUrl(): bool
    {
        return empty($this->uri->getScheme())
            && empty($this->uri->getAuthority());
    }

    private function isFullUrl(): bool
    {
        return \in_array($this->uri->getScheme(), ['http', 'https'])
            && !empty($this->uri->getAuthority())
            && (!$this->hasPath() || $this->hasPathWithLeadingSlash());
    }
}
