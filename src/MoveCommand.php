<?php

declare(strict_types=1);

namespace Ngmy\L4Dav;

use Psr\Http\Message\UriInterface;

class MoveCommand extends WebDavCommand
{
    /** @var MoveParameters */
    protected $parameters;

    /**
     * @param string|UriInterface $url
     */
    protected function __construct($url, MoveParameters $parameters, WebDavClientOptions $options)
    {
        $fullDestUrl = Url::createFullUrl($parameters->destUrl(), $options->baseUrl());
        parent::__construct('MOVE', $url, $options, new Headers([
            'Destination' => (string) $fullDestUrl,
        ]));
        $this->parameters = $parameters;
    }
}
