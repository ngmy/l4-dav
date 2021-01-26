<?php

declare(strict_types=1);

namespace Ngmy\L4Dav;

use Psr\Http\Message\ResponseInterface;
use SimpleXMLElement;

class ListResponseParser implements ResponseParserInterface
{
    /**
     * @param ResponseInterface $response
     * @return ListResponse
     */
    public function parse(ResponseInterface $response): ListResponse
    {
        return new ListResponse($response, $this->parseList($response));
    }

    /**
     * @param ResponseInterface $response
     * @return list<string>
     */
    private function parseList(ResponseInterface $response): array
    {
        if ($response->getStatusCode() < 200 || $response->getStatusCode() > 300) {
            return [];
        }

        $xml = \simplexml_load_string($response->getBody()->getContents(), SimpleXMLElement::class, 0, 'D', true);

        if ($xml === false) {
            return [];
        }

        $list = [];
        foreach ($xml->response as $element) {
            $list[] = (string) $element->href;
        }

        return $list;
    }
}
