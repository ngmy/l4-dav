<?php

declare(strict_types=1);

namespace Ngmy\L4Dav;

use Psr\Http\Message\ResponseInterface;
use SimpleXMLElement;

class PropfindResponse implements ResponseInterface
{
    use ResponseTrait;

    /** @var XmlResponseParser */
    private $parser;

    public function __construct(ResponseInterface $response)
    {
        $this->response = $response;
        $this->parser = new XmlResponseParser($response);
    }

    public function getXml(): SimpleXMLElement
    {
        return $this->parser->parse();
    }
}
