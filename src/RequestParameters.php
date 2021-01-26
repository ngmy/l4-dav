<?php

declare(strict_types=1);

namespace Ngmy\L4Dav;

class RequestParameters
{
    /** @var Headers */
    private $headers;

    /**
     * @return void
     */
    public function __construct()
    {
        $this->headers = new Headers();
    }

    /**
     * @param Headers $headers
     * @return self
     */
    public function setHeaders(Headers $headers): self
    {
        $this->headers = $headers;
        return $this;
    }

    /**
     * @return Headers
     */
    public function getHeaders(): Headers
    {
        return $this->headers;
    }
}
