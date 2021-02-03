<?php

declare(strict_types=1);

namespace Ngmy\L4Dav;

use Psr\Http\Message\UriInterface;

class ListCommand extends Command
{
    /** @var ListResponseParser */
    private $responseParser;

    /**
     * @param string|UriInterface $uri
     * @param string|int|null     $depth
     */
    protected function __construct(WebDavClientOptions $options, $uri, $depth = null)
    {
        parent::__construct($options, 'PROPFIND', $uri, new Headers([
            'Depth' => (string) new Depth($depth),
        ]));
        $this->responseParser = new ListResponseParser();
    }

    protected function doAfter(): void
    {
        $this->response = $this->responseParser->parse($this->response);
    }
}
