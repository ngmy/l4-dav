<?php

declare(strict_types=1);

namespace Ngmy\L4Dav;

use Psr\Http\Message\ResponseInterface;

class HeadResponse implements ResponseInterface
{
    use ResponseTrait;

    public function __construct(ResponseInterface $response)
    {
        $this->response = $response;
    }

    public function exists(): bool
    {
        return $this->response->getStatusCode() >= 200
            && $this->response->getStatusCode() < 300;
    }
}
