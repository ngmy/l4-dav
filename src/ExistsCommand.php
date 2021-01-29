<?php

declare(strict_types=1);

namespace Ngmy\L4Dav;

use Psr\Http\Message\ResponseInterface;

class ExistsCommand extends Command
{
    /**
     * @return void
     */
    public function __construct(WebDavClientOptions $options, string $uri)
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
