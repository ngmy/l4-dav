<?php

declare(strict_types=1);

namespace Ngmy\L4Dav;

interface ResponseInterface
{
    /**
     * Get the response body.
     *
     * @return string Returns the response body.
     */
    public function getBody();
    /**
     * Get the status code.
     *
     * @return int Returns the status code.
     */
    public function getStatus();
    /**
     * Get the status message.
     *
     * @return string Returns the status message.
     */
    public function getMessage();
}
