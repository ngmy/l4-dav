<?php

declare(strict_types=1);

namespace Ngmy\WebDav\Request;

use Http\Discovery\Psr17FactoryDiscovery;
use InvalidArgumentException;
use Ngmy\WebDav\Request;
use Psr\Http\Message\UriInterface;
use Throwable;

use function is_null;
use function sprintf;

abstract class Url
{
    /** @var UriInterface */
    protected $uri;

    abstract protected function validate(): void;

    /**
     * @param string|UriInterface $url
     */
    public static function createBaseUrl($url): Request\Url\Base
    {
        return new Request\Url\Base($url);
    }

    /**
     * @param string|UriInterface $url
     */
    public static function createRelativeUrl($url): Request\Url\Relative
    {
        return new Request\Url\Relative($url);
    }

    /**
     * @param string|UriInterface $url
     */
    public static function createFullUrl($url): Request\Url\Full
    {
        return new Request\Url\Full($url);
    }

    /**
     * @param string|UriInterface $url
     */
    public static function createRequestUrl($url, ?Request\Url\Base $baseUrl = null): Request\Url\Full
    {
        if (is_null($baseUrl)) {
            try {
                return new Request\Url\Full($url);
            } catch (InvalidArgumentException $e) {
                // no-op
            }
            throw new InvalidArgumentException(sprintf(
                'The request URL "%s" must be the full URL because the base URL is not specifided.',
                (string) $url
            ), 0, $e);
        } else {
            try {
                return $baseUrl->createFullUrlWithRelativeUrl(new Request\Url\Relative($url));
            } catch (InvalidArgumentException $e) {
                // no-op
            }
            throw new InvalidArgumentException(sprintf(
                'The request URL "%s" must be the relative URL because the base URL is specified.',
                (string) $url
            ), 0, $e);
        }
    }

    /**
     * @param string|UriInterface $url
     * @return Request\Url\Full|Request\Url\Relative
     */
    public static function createDestinationUrl($url, ?Request\Url\Base $baseUrl = null): self
    {
        if (is_null($baseUrl)) {
            try {
                return new Request\Url\Full($url);
            } catch (InvalidArgumentException $e1) {
                // no-op
            }
            try {
                return new Request\Url\Relative($url);
            } catch (InvalidArgumentException $e2) {
                // no-op
            }
            $e = self::withPrevious($e2, $e1);
            throw new InvalidArgumentException(sprintf(
                'The destination URL "%s" is invalid.',
                (string) $url
            ), 0, $e);
        } else {
            try {
                return $baseUrl->createFullUrlWithRelativeUrl(new Request\Url\Relative($url));
            } catch (InvalidArgumentException $e) {
                // no-op
            }
            throw new InvalidArgumentException(sprintf(
                'The destination URL "%s" must be the relative URL because the base URL is specified.',
                (string) $url
            ), 0, $e);
        }
    }

    public function getUri(): UriInterface
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
     * @param string|UriInterface $url
     */
    protected function __construct($url)
    {
        $this->uri = Psr17FactoryDiscovery::findUriFactory()->createUri((string) $url);
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
