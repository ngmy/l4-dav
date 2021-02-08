<?php

declare(strict_types=1);

namespace Ngmy\L4Dav;

use DOMDocument;
use Psr\Http\Message\ResponseInterface;
use RuntimeException;

class XmlResponseBodyParser
{
    /** @var ResponseInterface */
    private $response;

    public function __construct(ResponseInterface $response)
    {
        $this->response = $response;
    }

    public function parse(): DOMDocument
    {
        $xml = new DOMDocument('1.0', 'utf-8');
        $xml->preserveWhiteSpace = false;
        $xml->formatOutput = true;

        if (!\preg_match('~(text/xml|application/xml)~', \strtolower($this->response->getHeaderLine('Content-Type')))) {
            return $xml;
        }

        $this->response->getBody()->rewind();
        $body = $this->response->getBody()->getContents();
        $this->response->getBody()->rewind();

        if ($xml->loadXML($body) === false) {
            throw new RuntimeException();
        }

        return $xml;
    }
}
