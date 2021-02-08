<?php

declare(strict_types=1);

namespace Ngmy\L4Dav;

use DOMDocument;
use Psr\Http\Message\ResponseInterface;

class WebDavResponse implements ResponseInterface
{
    use Psr7ResponseTrait;

    /** @var XmlResponseBodyParser */
    private $responseBodyParser;

    public function __construct(ResponseInterface $response)
    {
        $this->response = $response;
        $this->responseBodyParser = new XmlResponseBodyParser($response);
    }

    public function getBodyAsXml(): DOMDocument
    {
        return $this->responseBodyParser->parse();
    }
}
