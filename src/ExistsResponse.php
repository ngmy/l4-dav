<?php

declare(strict_types=1);

namespace Ngmy\L4Dav;

use Psr\Http\Message\ResponseInterface;

class ExistsResponse implements ResponseInterface
{
    use ResponseTrait;

    /**
     * @param ResponseInterface $response
     * @return void
     */
    public function __construct(ResponseInterface $response)
    {
        $this->response = $response;
    }

    /**
     * @return bool
     */
    public function exists(): bool
    {
        return $this->response->getStatusCode() >= 200
            && $this->response->getStatusCode() < 300;
    }
}
