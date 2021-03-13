<?php

declare(strict_types=1);

namespace Ngmy\WebDav\Request;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\StreamInterface;

class Body
{
    /** @var StreamInterface|null */
    private $body;

    public function __construct(StreamInterface $body = null)
    {
        $this->body = $body;
    }

    public function provide(RequestInterface $request): RequestInterface
    {
        return $this->body
            ? $request->withBody($this->body)
            : $request;
    }
}
