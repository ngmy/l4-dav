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
        $dom = new DOMDocument('1.0', 'utf-8');
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;

        if ($this->response->getStatusCode() < 200 || $this->response->getStatusCode() > 300) {
            return $dom;
        }

        if ($dom->loadXML((string) $this->response->getBody()) === false) {
            throw new RuntimeException();
        }

        return $dom;
    }
}
