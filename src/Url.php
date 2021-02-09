<?php

declare(strict_types=1);

namespace Ngmy\L4Dav;

use Http\Discovery\Psr17FactoryDiscovery;
use InvalidArgumentException;
use Psr\Http\Message\UriInterface;
use Throwable;

abstract class Url
{
    /** @var UriInterface */
    protected $uri;

    abstract protected function validate(): void;

    /**
     * @param string|UriInterface $uri
     */
    public static function createBaseUrl($uri): BaseUrl
    {
        return new BaseUrl((string) $uri);
    }

    /**
     * @param string|UriInterface $uri
     */
    public static function createRelativeUrl($uri): RelativeUrl
    {
        return new RelativeUrl((string) $uri);
    }

    /**
     * @param string|UriInterface $uri
     */
    public static function createFullUrl($uri): FullUrl
    {
        return new FullUrl((string) $uri);
    }

    /**
     * @param string|UriInterface $uri
     * @throws InvalidArgumentException
     */
    public static function createRequestUrl($uri, ?BaseUrl $baseUrl = null): FullUrl
    {
        if (\is_null($baseUrl)) {
            try {
                return new FullUrl($uri);
            } catch (InvalidArgumentException $e) {
                // no-op
            }
            throw new InvalidArgumentException(\sprintf(
                'The request URL "%s" must be the full URL because the base URL is not specifided.',
                (string) $uri
            ), 0, $e);
        } else {
            try {
                return $baseUrl->createFullUrlWithRelativeUrl(new RelativeUrl($uri));
            } catch (InvalidArgumentException $e) {
                // no-op
            }
            throw new InvalidArgumentException(\sprintf(
                'The request URL "%s" must be the relative URL because the base URL is specified.',
                (string) $uri
            ), 0, $e);
        }
    }

    /**
     * @param string|UriInterface|Url $uri
     * @throws InvalidArgumentException
     * @return FullUrl|RelativeUrl
     */
    public static function createDestUrl($uri, ?BaseUrl $baseUrl = null): Url
    {
        if (\is_null($baseUrl)) {
            try {
                return new FullUrl($uri);
            } catch (InvalidArgumentException $e1) {
                // no-op
            }
            try {
                return new RelativeUrl($uri);
            } catch (InvalidArgumentException $e2) {
                // no-op
            }
            $e = self::withPrevious($e2, $e1);
            throw new InvalidArgumentException(\sprintf(
                'The destination URL "%s" is invalid.',
                (string) $uri
            ), 0, $e);
        } else {
            try {
                return $baseUrl->createFullUrlWithRelativeUrl(new RelativeUrl($uri));
            } catch (InvalidArgumentException $e) {
                // no-op
            }
            throw new InvalidArgumentException(\sprintf(
                'The destination URL "%s" must be the relative URL because the base URL is specified.',
                (string) $uri
            ), 0, $e);
        }
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
    protected function __construct($uri)
    {
        $this->uri = Psr17FactoryDiscovery::findUriFactory()->createUri((string) $uri);
        $this->validate();
    }

    /**
     * @see https://qiita.com/mpyw/items/7f5a9fe6472f38352d96
     */
    private static function withPrevious(Throwable $e, Throwable $previous): Throwable
    {
        try {
            try {
                throw $previous;
            } finally {
                throw $e;
            }
        } catch (Throwable $e) {
            return $e;
        }
    }
}
