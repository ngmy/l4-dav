<?php

declare(strict_types=1);

namespace Ngmy\L4Dav;

use Psr\Http\Message\UriInterface;

class HeadCommand extends WebDavCommand
{
    /** @var HeadParameters */
    protected $parameters;

    /**
     * @param string|UriInterface $url
     */
    protected function __construct($url, HeadParameters $parameters, WebDavClientOptions $options)
    {
        parent::__construct('HEAD', $url, $options);
        $this->parameters = $parameters;
    }

    protected function doAfter(): void
    {
        $this->response = new HeadResponse($this->response);
    }
}
