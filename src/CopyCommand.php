<?php

declare(strict_types=1);

namespace Ngmy\L4Dav;

use League\Uri\Components\Path;
use Psr\Http\Message\UriInterface;

class CopyCommand extends Command
{
    /**
     * @param string|UriInterface $srcUri
     * @param string|UriInterface $destUri
     * @return void
     */
    public function __construct(WebDavClientOptions $options, $srcUri, $destUri)
    {
        $destUri = !\is_null($options->baseUrl())
            ? $options->baseUrl()->withPath(new Path($destUri))->uri()
            : new AbsoluteUri($destUri);
        parent::__construct($options, 'Copy', $srcUri, new Headers([
            'Destination' => (string) $destUri,
        ]));
    }
}
