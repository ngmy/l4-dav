<?php

declare(strict_types=1);

namespace Ngmy\L4Dav;

use Psr\Http\Message\UriInterface;

class ExistsCommand extends Command
{
    /**
     * @param string|UriInterface $uri
     */
    protected function __construct(WebDavClientOptions $options, $uri)
    {
        parent::__construct($options, 'HEAD', $uri);
    }

    protected function doAfter(): void
    {
        $this->response = new ExistsResponse($this->response);
    }
}
