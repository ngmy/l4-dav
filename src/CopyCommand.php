<?php

declare(strict_types=1);

namespace Ngmy\L4Dav;

use Psr\Http\Message\UriInterface;

class CopyCommand extends WebDavCommand
{
    /** @var CopyParameters */
    protected $parameters;

    /**
     * @param string|UriInterface $url
     */
    protected function __construct($url, CopyParameters $parameters, WebDavClientOptions $options)
    {
        $fullDestUrl = Url::createFullUrl($parameters->destUrl(), $options->baseUrl());
        parent::__construct('COPY', $url, $options, new Headers([
            'Destination' => (string) $fullDestUrl,
            'Overwrite' => (string) $parameters->overwrite(),
        ]));
        $this->parameters = $parameters;
    }
}
