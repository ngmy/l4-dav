<?php

declare(strict_types=1);

namespace Ngmy\L4Dav;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriInterface;

class ExistsCommand extends Command
{
    /**
     * @param string|UriInterface $uri
     */
    public function __construct(WebDavClientOptions $options, $uri)
    {
        parent::__construct($options, 'HEAD', $uri);
    }

    /**
     * @inheritdoc
     */
    protected function getResponse(): ResponseInterface
    {
        return new ExistsResponse(parent::getResponse());
    }
}
