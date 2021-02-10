<?php

declare(strict_types=1);

namespace Ngmy\PhpWebDav;

use DOMDocument;
use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class WebDavResponse implements MessageInterface, ResponseInterface
{
    /**
     * An instance of the any class that implements the PSR-7 ResponseInterface.
     *
     * @var ResponseInterface
     */
    private $response;
    /**
     * The parser of the XML response body.
     *
     * @var XmlResponseBodyParser
     */
    private $responseBodyParser;

    /**
     * @param ResponseInterface $response An instance of the any class that implements the PSR-7 ResponseInterface
     */
    public function __construct(ResponseInterface $response)
    {
        $this->response = $response;
        $this->responseBodyParser = new XmlResponseBodyParser($response);
    }

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

    /**
     * Get the response body as XML.
     *
     * @return DOMDocument The response body as XML
     */
    public function getBodyAsXml(): DOMDocument
    {
        return $this->responseBodyParser->parse();
    }
}
