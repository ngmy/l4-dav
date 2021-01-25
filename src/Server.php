<?php

declare(strict_types=1);

namespace Ngmy\L4Dav;

class Server
{
    /** @var Url */
    private $url;
    /** @var int */
    private $port;

    /**
     * @param string $url  The URL of the WebDAV server.
     * @param int    $port The port of the WebDAV server.
     * @return self
     */
    public static function of(string $url, int $port = 80): self
    {
        return new self(new Url($url), $port);
    }

    /**
     * Create a new Client class object.
     *
     * @param Url $url  The URL of the WebDAV server.
     * @param int $port The port of the WebDAV server.
     * @return void
     */
    public function __construct(Url $url, int $port = 80)
    {
        $this->url = $url;
        $this->port = $port;
    }

    public function url(): Url
    {
        return $this->url->withoutPort();
    }

    public function port(): int
    {
        return $this->port ?? $this->url->parse()['port'];
    }
}
