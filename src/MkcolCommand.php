<?php

declare(strict_types=1);

namespace Ngmy\L4Dav;

use Psr\Http\Message\UriInterface;

class MkcolCommand extends Command
{
    /** @var MkcolParameters */
    protected $parameters;

    /**
     * @param string|UriInterface $requestUri
     */
    protected function __construct($requestUri, MkcolParameters $parameters, WebDavClientOptions $options)
    {
        parent::__construct('MKCOL', $requestUri, $options);
        $this->parameters = $parameters;
    }
}
