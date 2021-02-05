<?php

declare(strict_types=1);

namespace Ngmy\L4Dav;

use Psr\Http\Message\UriInterface;

class PropfindCommand extends Command
{
    /** @var PropfindParameters */
    protected $parameters;

    /**
     * @param UriInterface|string $requestUri
     */
    protected function __construct($requestUri, PropfindParameters $parameters, WebDavClientOptions $options)
    {
        parent::__construct('PROPFIND', $requestUri, $options, new Headers([
            'Depth' => (string) $parameters->depth(),
        ]));
        $this->parameters = $parameters;
    }

    protected function doAfter(): void
    {
        $this->response = new PropfindResponse($this->response);
    }
}
