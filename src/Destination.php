<?php

declare(strict_types=1);

namespace Ngmy\PhpWebDav;

use Psr\Http\Message\UriInterface;

class Destination
{
    private const HEADER_NAME = 'Destination';

    /** @var FullUrl|RelativeUrl */
    private $destinationUrl;

    /**
     * @param string|UriInterface $url
     */
    public static function createFromUrl($url, ?BaseUrl $baseUrl = null): self
    {
        $destinationUrl = Url::createDestinationUrl($url, $baseUrl);
        return new self($destinationUrl);
    }

    /**
     * @param FullUrl|RelativeUrl $destinationUrl
     */
    public function __construct($destinationUrl)
    {
        $this->destinationUrl = $destinationUrl;
    }

    public function __toString(): string
    {
        return (string) $this->destinationUrl;
    }

    public function provide(Headers $headers): Headers
    {
        return $headers->withHeader(self::HEADER_NAME, (string) $this->destinationUrl);
    }
}
