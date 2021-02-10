<?php

declare(strict_types=1);

namespace Ngmy\PhpWebDav;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

/**
 * The trait to implement the PSR-7 ResponseInterface.
 *
 * @see https://www.php-fig.org/psr/psr-7/
 */
trait Psr7ResponseTrait
{
    /** @var ResponseInterface An instance of the any class that implements the PSR-7 ResponseInterface */
    private $response;

    /**
     * {@inheritdoc}
     */
    public function getProtocolVersion()
    {
        return $this->response->getProtocolVersion();
    }

    /**
     * {@inheritdoc}
     */
    public function withProtocolVersion($version)
    {
        $new = clone $this;
        $new->response = $this->response->withProtocolVersion($version);
        return $new;
    }

    /**
     * {@inheritdoc}
     */
    public function getHeaders()
    {
        return $this->response->getHeaders();
    }

    /**
     * {@inheritdoc}
     */
    public function hasHeader($header)
    {
        return $this->response->hasHeader($header);
    }

    /**
     * {@inheritdoc}
     */
    public function getHeader($header)
    {
        return $this->response->getHeader($header);
    }

    /**
     * {@inheritdoc}
     */
    public function getHeaderLine($header)
    {
        return $this->response->getHeaderLine($header);
    }

    /**
     * {@inheritdoc}
     */
    public function withHeader($header, $value)
    {
        $new = clone $this;
        $new->response = $this->response->withHeader($header, $value);
        return $new;
    }

    /**
     * {@inheritdoc}
     */
    public function withAddedHeader($header, $value)
    {
        $new = clone $this;
        $new->response = $this->response->withAddedHeader($header, $value);
        return $new;
    }

    /**
     * {@inheritdoc}
     */
    public function withoutHeader($header)
    {
        $new = clone $this;
        $new->response = $this->response->withoutHeader($header);
        return $new;
    }

    /**
     * {@inheritdoc}
     */
    public function getBody()
    {
        return $this->response->getBody();
    }

    /**
     * {@inheritdoc}
     */
    public function withBody(StreamInterface $body)
    {
        $new = clone $this;
        $new->response = $this->response->withBody($body);
        return $new;
    }

    /**
     * {@inheritdoc}
     */
    public function getStatusCode()
    {
        return $this->response->getStatusCode();
    }

    /**
     * {@inheritdoc}
     */
    public function getReasonPhrase()
    {
        return $this->response->getReasonPhrase();
    }

    /**
     * {@inheritdoc}
     */
    public function withStatus($code, $reasonPhrase = '')
    {
        $new = clone $this;
        $new->response = $this->response->withStatus($code, $reasonPhrase);
        return $new;
    }
}
