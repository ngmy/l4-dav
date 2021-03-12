<?php

declare(strict_types=1);

namespace Ngmy\WebDav\Response\Body\Parser;

use DOMDocument;
use Psr\Http\Message\ResponseInterface;
use RuntimeException;

class Xml
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
            throw new RuntimeException('Failed to parse the XML response body.');
        }

        return $xml;
    }
}
