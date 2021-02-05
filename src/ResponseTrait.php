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
        return $this->response->getProtocolVersion();
    }

    public function withProtocolVersion($version)
    {
        $new = clone $this;
        $new->response = $this->response->withProtocolVersion($version);
        return $new;
    }

    public function getHeaders()
    {
        return $this->response->getHeaders();
    }

    public function hasHeader($header)
    {
        return $this->response->hasHeader($header);
    }

    public function getHeader($header)
    {
        return $this->response->getHeader($header);
    }

    public function getHeaderLine($header)
    {
        return $this->response->getHeaderLine($header);
    }

    public function withHeader($header, $value)
    {
        $new = clone $this;
        $new->response = $this->response->withHeader($header, $value);
        return $new;
    }

    public function withAddedHeader($header, $value)
    {
        $new = clone $this;
        $new->response = $this->response->withAddedHeader($header, $value);
        return $new;
    }

    public function withoutHeader($header)
    {
        $new = clone $this;
        $new->response = $this->response->withoutHeader($header);
        return $new;
    }

    public function getBody()
    {
        return $this->response->getBody();
    }

    public function withBody(StreamInterface $body)
    {
        $new = clone $this;
        $new->response = $this->response->withBody($body);
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
