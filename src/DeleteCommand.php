<?php

declare(strict_types=1);

namespace Ngmy\L4Dav;

use Psr\Http\Message\UriInterface;

class DeleteCommand extends Command
{
    /**
     * @param string|UriInterface $uri
     * @return void
     */
    public function __construct(WebDavClientOptions $options, $uri)
    {
        parent::__construct($options, 'DELETE', $uri);
    }
}
