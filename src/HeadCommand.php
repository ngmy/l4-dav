<?php

declare(strict_types=1);

namespace Ngmy\L4Dav;

use Psr\Http\Message\UriInterface;

class HeadCommand extends Command
{
    /** @var HeadParameters */
    protected $parameters;

    /**
     * @param string|UriInterface $requestUri
     */
    protected function __construct($requestUri, HeadParameters $parameters, WebDavClientOptions $options)
    {
        parent::__construct('HEAD', $requestUri, $options);
        $this->parameters = $parameters;
    }

    protected function doAfter(): void
    {
        $this->response = new HeadResponse($this->response);
    }
}
