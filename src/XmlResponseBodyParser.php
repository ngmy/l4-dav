<?php

declare(strict_types=1);

namespace Ngmy\L4Dav;

use Psr\Http\Message\ResponseInterface;
use RuntimeException;
use SimpleXMLElement;

class XmlResponseBodyParser
{
    /** @var ResponseInterface */
    private $response;

    public function __construct(ResponseInterface $response)
    {
        $this->response = $response;
    }

    public function parse(): SimpleXMLElement
    {
        if ($this->response->getStatusCode() < 200 || $this->response->getStatusCode() > 300) {
            return $this->createEmptyXml();
        }

        $xml = \simplexml_load_string((string) $this->response->getBody(), SimpleXMLElement::class);

        if ($xml === false) {
            throw new RuntimeException();
        }

        return $xml->children('DAV:');
    }

    private function createEmptyXml(): SimpleXMLElement
    {
        return new SimpleXMLElement('<?xml version="1.0" encoding="utf-8"?><root></root>');
    }
}
