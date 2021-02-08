<?php

declare(strict_types=1);

namespace Ngmy\L4Dav;

use Psr\Http\Message\ResponseInterface;

class WebDavResponse implements ResponseInterface
{
    use Psr7ResponseTrait;
    use WebDavResponseHelpers;

    public function __construct(ResponseInterface $response)
    {
        $this->response = $response;
    }
}
