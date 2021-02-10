<?php

declare(strict_types=1);

namespace Ngmy\PhpWebDav;

use DOMDocument;
use Psr\Http\Message\ResponseInterface;

class WebDavResponse implements ResponseInterface
{
    use Psr7ResponseTrait;

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
     * Get the response body as XML.
     *
     * @return DOMDocument The response body as XML
     */
    public function getBodyAsXml(): DOMDocument
    {
        return $this->responseBodyParser->parse();
    }
}
