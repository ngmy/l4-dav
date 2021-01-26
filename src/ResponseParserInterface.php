<?php

declare(strict_types=1);

namespace Ngmy\L4Dav;

use Psr\Http\Message\ResponseInterface;

interface ResponseParserInterface
{
    /**
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    public function parse(ResponseInterface $response): ResponseInterface;
}
