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

    abstract protected function validate(): void;

    /**
     * @param CandidateUrl|string|UriInterface $uri
     */
    public static function createBaseUrl($uri): BaseUrl
    {
        return new BaseUrl((string) $uri);
    }

    /**
     * @param CandidateUrl|string|UriInterface $uri
     */
    public static function createRelativeUrl($uri): RelativeUrl
    {
        return new RelativeUrl((string) $uri);
    }

    /**
     * @param CandidateUrl|string|UriInterface $uri
     */
    public static function createFullUrl($uri): FullUrl
    {
        return new FullUrl((string) $uri);
    }

    /**
     * @param CandidateUrl|string|UriInterface $uri
     * @throws InvalidArgumentException
     */
    public static function createRequestUrl($uri, ?BaseUrl $baseUrl = null): FullUrl
    {
        $candidate = new CandidateUrl(Psr17FactoryDiscovery::findUriFactory()->createUri((string) $uri));

        if (\is_null($baseUrl)) {
            if ($candidate->isFullUrl()) {
                return new FullUrl($candidate);
            }
            throw new InvalidArgumentException(
                \sprintf(
                    'The request URL "%s" must be the full URL because the base URL is not specifided.',
                    (string) $uri
                )
            );
        } else {
            if ($candidate->isRelativeUrl()) {
                return $baseUrl->createFullUrlWithRelativeUrl(new RelativeUrl($candidate));
            }
            throw new InvalidArgumentException(
                \sprintf(
                    'The request URL "%s" must be the relative URL because the base URL is specified.',
                    (string) $uri
                )
            );
        }
    }

    /**
     * @param string|UriInterface|Url $uri
     * @throws InvalidArgumentException
     * @return FullUrl|RelativeUrl
     */
    public static function createDestUrl($uri, ?BaseUrl $baseUrl = null)
    {
        $candidate = new CandidateUrl(Psr17FactoryDiscovery::findUriFactory()->createUri((string) $uri));

        if (\is_null($baseUrl)) {
            if ($candidate->isFullUrl()) {
                return new FullUrl($candidate);
            }
            if ($candidate->isRelativeUrl()) {
                return new RelativeUrl($candidate);
            }
            throw new InvalidArgumentException(
                \sprintf(
                    'The destination URL "%s" is invalid.',
                    (string) $uri
                )
            );
        } else {
            if ($candidate->isRelativeUrl()) {
                return $baseUrl->createFullUrlWithRelativeUrl(new RelativeUrl($candidate));
            }
            throw new InvalidArgumentException(
                \sprintf(
                    'The destination URL "%s" must be the relative URL because the base URL is specified.',
                    (string) $uri
                )
            );
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
}
