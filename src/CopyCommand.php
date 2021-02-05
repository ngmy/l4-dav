<?php

declare(strict_types=1);

namespace Ngmy\L4Dav;

use Psr\Http\Message\UriInterface;

class CopyCommand extends Command
{
    /** @var CopyParameters */
    protected $parameters;

    /**
     * @param string|UriInterface $url
     */
    protected function __construct($url, CopyParameters $parameters, WebDavClientOptions $options)
    {
        $fullDestUri = Url::createFullUrl($parameters->destUri(), $options->baseUrl());
        parent::__construct('Copy', $url, $options, new Headers([
            'Destination' => (string) $fullDestUri,
            'Overwrite' => (string) $parameters->overwrite(),
        ]));
        $this->parameters = $parameters;
    }
}
