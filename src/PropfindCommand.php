<?php

declare(strict_types=1);

namespace Ngmy\L4Dav;

use Psr\Http\Message\UriInterface;

class PropfindCommand extends WebDavCommand
{
    /** @var PropfindParameters */
    protected $parameters;

    /**
     * @param string|UriInterface $url
     */
    protected function __construct($url, PropfindParameters $parameters, WebDavClientOptions $options)
    {
        parent::__construct('PROPFIND', $url, $options, new Headers([
            'Depth' => (string) $parameters->depth(),
        ]));
        $this->parameters = $parameters;
    }

    protected function doAfter(): void
    {
        $this->response = new PropfindResponse($this->response);
    }
}
