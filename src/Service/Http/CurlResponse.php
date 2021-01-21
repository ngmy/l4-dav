<?php

declare(strict_types=1);

namespace Ngmy\L4Dav\Service\Http;

use anlutro\cURL\Response;

class CurlResponse extends Response implements ResponseInterface
{
    /**
     * Get the response body.
     *
     * @return string Returns the response body.
     */
    public function getBody(): string
    {
        return $this->body;
    }

    /**
     * Get the status code.
     *
     * @return int Returns the status code.
     */
    public function getStatus(): int
    {
        return (int) $this->statusCode;
    }

    /**
     * Get the status message.
     *
     * @return string Returns the status message.
     */
    public function getMessage(): string
    {
        return $this->statusText;
    }
}
