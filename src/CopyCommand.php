<?php

declare(strict_types=1);

namespace Ngmy\L4Dav;

use Psr\Http\Message\UriInterface;

class CopyCommand extends Command
{
    /**
     * @param string|UriInterface $srcUri
     * @param string|UriInterface $destUri
     */
    protected function __construct(WebDavClientOptions $options, $srcUri, $destUri, bool $overwrite = false)
    {
        $fullDestUri = Url::createFullUrl($destUri, $options->baseUrl());
        parent::__construct($options, 'Copy', $srcUri, new Headers([
            'Destination' => (string) $fullDestUri,
            'Overwrite' => $overwrite ? 'T' : 'F',
        ]));
    }
}
