<?php

declare(strict_types=1);

namespace Ngmy\L4Dav;

use Psr\Http\Message\UriInterface;

class ListCommand extends Command
{
    /**
     * @param string|UriInterface $uri
     * @param int|string|null     $depth
     */
    protected function __construct(WebDavClientOptions $options, $uri, $depth = null)
    {
        parent::__construct($options, 'PROPFIND', $uri, new Headers([
            'Depth' => (string) new Depth($depth),
        ]));
    }

    protected function doAfter(): void
    {
        $this->response = new ListResponse($this->response);
    }
}
