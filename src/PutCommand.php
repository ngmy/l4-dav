<?php

declare(strict_types=1);

namespace Ngmy\L4Dav;

use Http\Discovery\Psr17FactoryDiscovery;
use Psr\Http\Message\UriInterface;
use RuntimeException;

class PutCommand extends Command
{
    /** @var PutParameters */
    protected $parameters;

    /**
     * @param string|UriInterface $url
     * @throws RuntimeException
     */
    protected function __construct($url, PutParameters $parameters, WebDavClientOptions $options)
    {
        $fh = \fopen($parameters->srcPath(), 'r');
        if ($fh === false) {
            throw new RuntimeException('Failed to open file (' . $parameters->srcPath() . ')');
        }
        $body = Psr17FactoryDiscovery::findStreamFactory()->createStreamFromResource($fh);
        parent::__construct('PUT', $url, $options, new Headers([
            'Content-Length' => (string) \filesize($parameters->srcPath()),
        ]), $body);
        $this->parameters = $parameters;
    }
}
