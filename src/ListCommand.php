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
     */
    protected function __construct(WebDavClientOptions $options, $uri)
    {
        parent::__construct($options, 'PROPFIND', $uri, new Headers([
            'Depth' => '1',
        ]));
        $this->responseParser = new ListResponseParser();
    }

    protected function doAfter(): void
    {
        $this->response = $this->responseParser->parse($this->response);
    }
}
