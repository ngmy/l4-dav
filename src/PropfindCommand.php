<?php

declare(strict_types=1);

namespace Ngmy\L4Dav;

use Psr\Http\Message\UriInterface;

class PropfindCommand extends Command
{
    /**
     * @param UriInterface|string $uri
     * @param Depth               $depth
     */
    protected function __construct(WebDavClientOptions $options, $uri, Depth $depth)
    {
        parent::__construct($options, 'PROPFIND', $uri, new Headers([
            'Depth' => (string) $depth,
        ]));
    }

    protected function doAfter(): void
    {
        $this->response = new PropfindResponse($this->response);
    }
}
