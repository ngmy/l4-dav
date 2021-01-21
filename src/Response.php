<?php

declare(strict_types=1);

namespace Ngmy\L4Dav;

use anlutro\cURL\Response as CurlResponse;

class Response implements ResponseInterface
{
    /** @var CurlResponse */
    private $response;

    public function __construct(CurlResponse $response)
    {
        $this->response = $response;
    }

    /**
     * Get the response body.
     *
     * @return string Returns the response body.
     */
    public function getBody(): string
    {
        return $this->response->body;
    }

    /**
     * Get the status code.
     *
     * @return int Returns the status code.
     */
    public function getStatus(): int
    {
        return (int) $this->response->statusCode;
    }

    /**
     * Get the status message.
     *
     * @return string Returns the status message.
     */
    public function getMessage(): string
    {
        return $this->response->statusText;
    }
}
