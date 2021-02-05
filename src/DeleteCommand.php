<?php

declare(strict_types=1);

namespace Ngmy\L4Dav;

use Psr\Http\Message\UriInterface;

class DeleteCommand extends Command
{
    /** @var DeleteParameters */
    protected $parameters;

    /**
     * @param string|UriInterface $requestUri
     */
    protected function __construct($requestUri, DeleteParameters $parameters, WebDavClientOptions $options)
    {
        parent::__construct('DELETE', $requestUri, $options);
        $this->parameters = $parameters;
    }
}
