<?php

declare(strict_types=1);

namespace Ngmy\L4Dav;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriInterface;
use SimpleXMLElement;

class ListResponse implements ResponseInterface
{
    use ResponseTrait;

    /** @var ListResponseParser */
    private $parser;

    public function __construct(ResponseInterface $response)
    {
        $this->response = $response;
        $this->parser = new ListResponseParser($response);
    }

    /**
     * @return list<SimpleXMLElement>
     */
    public function getAllFilesAndDirectories(): array
    {
        // TODO なんで/webdav/ ってパスが付くの？
        $filesAndDirectories = [];
        foreach ($this->xml()->response as $element) {
            $filesAndDirectories[] = $element->href;
        }
        return $filesAndDirectories;
    }

    /**
     * @return list<SimpleXMLElement>
     */
    public function getAllFiles(): array
    {
        $xml = $this->xml();
        $files = [];
        foreach ($xml->response as $element) {
            if (\property_exists($element->propstat->prop->resourcetype, 'collection')) {
                continue;
            }
            $files[] = $element->href;
        }
        return $files;
    }

    /**
     * @return list<SimpleXMLElement>
     */
    public function getAllDirectories(): array
    {
        $xml = $this->xml();
        $directories = [];
        foreach ($xml->response as $element) {
            if (!\property_exists($element->propstat->prop->resourcetype, 'collection')) {
                continue;
            }
            $directories[] = $element->href;
        }
        return $directories;
    }

    /**
     * @param string|UriInterface $uri
     */
    public function getProperty($uri): SimpleXMLElement
    {
        // TODO 単数？複数？
        $xml = $this->xml();
        foreach ($xml->response as $element) {
            if ($element->href != $uri) {
                continue;
            }
            return $element->propstat->prop;
        }
        return $this->emptySimpleXmlElement();
    }

    /**
     * @return array<string, SimpleXMLElement>
     */
    public function getAllProperties(): array
    {
        $xml = $this->xml();
        $properties = [];
        foreach ($xml->response as $element) {
            $properties[(string) $element->href] = $element->propstat->prop;
        }
        return $properties;
    }

    public function getResponse(): SimpleXMLElement
    {
        return $this->xml();
    }

    public function xml(): SimpleXMLElement
    {
        return $this->parser->parse();
    }

    private function emptySimpleXmlElement(): SimpleXMLElement
    {
        return new SimpleXMLElement('<?xml version="1.0" encoding="utf-8"?><root></root>');
    }
}
