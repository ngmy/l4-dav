<?php

declare(strict_types=1);

namespace Ngmy\L4Dav;

use Psr\Http\Message\UriInterface;

class MakeDirectoryCommand extends Command
{
    /**
     * @param string|UriInterface $uri
     */
    protected function __construct(WebDavClientOptions $options, $uri)
    {
        parent::__construct($options, 'MKCOL', $uri);
    }
}
