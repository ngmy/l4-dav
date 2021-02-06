<?php

declare(strict_types=1);

namespace Ngmy\L4Dav;

use Psr\Http\Message\UriInterface;

class MkcolCommand extends WebDavCommand
{
    /** @var MkcolParameters */
    protected $parameters;

    /**
     * @param string|UriInterface $url
     */
    protected function __construct($url, MkcolParameters $parameters, WebDavClientOptions $options)
    {
        parent::__construct('MKCOL', $url, $options);
        $this->parameters = $parameters;
    }
}
