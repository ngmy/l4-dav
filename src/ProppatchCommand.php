<?php

declare(strict_types=1);

namespace Ngmy\L4Dav;

use InvalidArgumentException;
use Psr\Http\Message\UriInterface;
use SimpleXMLElement;

class ProppatchCommand extends Command
{
    /** @var ProppatchParameters */
    protected $parameters;

    /**
     * @param string|UriInterface $url
     */
    protected function __construct($url, ProppatchParameters $parameters, WebDavClientOptions $options)
    {
        parent::__construct('PROPPATCH', $url, $options, new Headers(), $this->configureBody($parameters));
        $this->parameters = $parameters;
    }

    private function configureBody(ProppatchParameters $parameters): string
    {
        $xml = new SimpleXMLElement(<<<XML
<?xml version="1.0" encoding="utf-8"?>
<D:propertyupdate xmlns:D="DAV:">
</D:propertyupdate>
XML);

        Hoge::createSet($parameters->propertiesToSet())->hoge($xml);
        Hoge::createRemove($parameters->propertiesToRemove())->hoge($xml);

        $body = $xml->asXML();

        if ($body === false) {
            throw new InvalidArgumentException();
        }

        return $body;
    }
}
