<?php

declare(strict_types=1);

namespace Ngmy\WebDav\Http\Client\Option;

use League\Uri\Components\Port as LeaguePort;

class Port
{
    /** @var LeaguePort */
    private $port;

    public static function createFromNumber(?int $port = null): self
    {
        return new self(new LeaguePort($port));
    }

    public function __construct(LeaguePort $port)
    {
        $this->port = $port;
    }

    /**
     * @param array<int, mixed> $curlOptions
     * @return array<int, mixed>
     */
    public function provide(array $curlOptions): array
    {
        if (!\is_null($this->port->toInt())) {
            $curlOptions[\CURLOPT_PORT] = $this->port->toInt();
        }
        return $curlOptions;
    }
}
