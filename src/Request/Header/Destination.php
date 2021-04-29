<?php

declare(strict_types=1);

namespace Ngmy\WebDav\Request\Header;

use Ngmy\WebDav\Request;
use Psr\Http\Message\UriInterface;

class Destination
{
    private const HEADER_NAME = 'Destination';

    /** @var Request\Url\Full|Request\Url\Relative */
    private $destinationUrl;

    /**
     * @param string|UriInterface $url
     */
    public static function createFromUrl($url, ?Request\Url\Base $baseUrl = null): self
    {
        $destinationUrl = Request\Url::createDestinationUrl($url, $baseUrl);
        return new self($destinationUrl);
    }

    /**
     * @param Request\Url\Full|Request\Url\Relative $destinationUrl
     */
    public function __construct($destinationUrl)
    {
        $this->destinationUrl = $destinationUrl;
    }

    public function __toString(): string
    {
        return (string) $this->destinationUrl;
    }

    public function provide(Request\Headers $headers): Request\Headers
    {
        return $headers->withHeader(self::HEADER_NAME, (string) $this);
    }
}
