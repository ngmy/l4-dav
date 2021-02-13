<?php

declare(strict_types=1);

namespace Ngmy\PhpWebDav;

class Destination
{
    private const HEADER_NAME = 'Destination';

    /** @var FullUrl|RelativeUrl */
    private $destinationUrl;

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
