<?php

declare(strict_types=1);

namespace Ngmy\L4Dav;

use Psr\Http\Message\UriInterface;

class DeleteCommand extends WebDavCommand
{
    /** @var DeleteParameters */
    protected $parameters;

    /**
     * @param string|UriInterface $url
     */
    protected function __construct($url, DeleteParameters $parameters, WebDavClientOptions $options)
    {
        parent::__construct('DELETE', $url, $options);
        $this->parameters = $parameters;
    }
}
