<?php

declare(strict_types=1);

namespace Ngmy\L4Dav;

use Psr\Http\Message\UriInterface;

class MoveCommand extends Command
{
    /**
     * @param string|UriInterface $srcUri
     * @param string|UriInterface $destUri
     */
    public function __construct(WebDavClientOptions $options, $srcUri, $destUri)
    {
        $destUri = FullUrl::createFromBaseUrl($destUri, $options->baseUrl());
        parent::__construct($options, 'MOVE', $srcUri, new Headers([
            'Destination' => (string) $destUri,
        ]));
    }
}
