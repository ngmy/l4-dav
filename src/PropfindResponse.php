<?php

declare(strict_types=1);

namespace Ngmy\L4Dav;

use Psr\Http\Message\ResponseInterface;
use SimpleXMLElement;

class PropfindResponse implements ResponseInterface
{
    use ResponseTrait;

    /** @var XmlResponseBodyParser */
    private $parser;

    public function __construct(ResponseInterface $response)
    {
        $this->response = $response;
        $this->parser = new XmlResponseBodyParser($response);
    }

    public function getXml(): SimpleXMLElement
    {
        return $this->parser->parse();
    }
}
