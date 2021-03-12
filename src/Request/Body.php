<?php

declare(strict_types=1);

namespace Ngmy\WebDav\Request;

use GuzzleHttp\Psr7\Utils;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\StreamInterface;

class Body
{
    /** @var resource|StreamInterface|string|null */
    private $body;

    /**
     * @param resource|StreamInterface|string|null $body
     */
    public function __construct($body = null)
    {
        $this->body = $body;
    }

    public function provide(RequestInterface $request): RequestInterface
    {
        return $this->body
            ? $request->withBody(Utils::streamFor($this->body))
            : $request;
    }
}
