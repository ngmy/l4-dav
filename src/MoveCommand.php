<?php

declare(strict_types=1);

namespace Ngmy\L4Dav;

use Psr\Http\Message\UriInterface;

class MoveCommand extends Command
{
    /** @var MoveParameters */
    protected $parameters;

    /**
     * @param string|UriInterface $url
     */
    protected function __construct($url, MoveParameters $parameters, WebDavClientOptions $options)
    {
        $fullDestUri = Url::createFullUrl($parameters->destUri(), $options->baseUrl());
        parent::__construct('MOVE', $url, $options, new Headers([
            'Destination' => (string) $fullDestUri,
        ]));
        $this->parameters = $parameters;
    }
}
