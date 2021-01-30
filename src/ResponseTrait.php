<?php

declare(strict_types=1);

namespace Ngmy\L4Dav;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

trait ResponseTrait
{
    /** @var ResponseInterface */
    private $response;

    public function getProtocolVersion()
    {
        return $this->getProtocolVersion();
    }

    public function withProtocolVersion($version)
    {
        $new = clone $this;
        $new->response = $this->withProtocolVersion($version);
        return $new;
    }

    public function getHeaders()
    {
        return $this->getHeaders();
    }

    public function hasHeader($header)
    {
        return $this->hasHeader($header);
    }

    public function getHeader($header)
    {
        return $this->getHeader($header);
    }

    public function getHeaderLine($header)
    {
        return $this->getHeaderLine($header);
    }

    public function withHeader($header, $value)
    {
        $new = clone $this;
        $new->response = $this->withHeader($header, $value);
        return $new;
    }

    public function withAddedHeader($header, $value)
    {
        $new = clone $this;
        $new->response = $this->withAddedHeader($header, $value);
        return $new;
    }

    public function withoutHeader($header)
    {
        $new = clone $this;
        $new->response = $this->withoutHeader($header);
        return $new;
    }

    public function getBody()
    {
        return $this->getBody();
    }

    public function withBody(StreamInterface $body)
    {
        $new = clone $this;
        $new->response = $this->withBody($body);
        return $new;
    }

    public function getStatusCode()
    {
        return $this->response->getStatusCode();
    }

    public function getReasonPhrase()
    {
        return $this->response->getReasonPhrase();
    }

    public function withStatus($code, $reasonPhrase = '')
    {
        $new = clone $this;
        $new->response = $this->response->withStatus($code, $reasonPhrase);
        return $new;
    }
}
