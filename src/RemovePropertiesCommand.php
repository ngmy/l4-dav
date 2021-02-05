<?php

declare(strict_types=1);

namespace Ngmy\L4Dav;

use InvalidArgumentException;
use Psr\Http\Message\UriInterface;
use SimpleXMLElement;

class RemovePropertiesCommand extends Command
{
    /**
     * @param string|UriInterface                     $uri
     * @param list<SimpleXMLElement>|SimpleXMLElement $properties
     */
    protected function __construct(WebDavClientOptions $options, $uri, $properties)
    {
        parent::__construct($options, 'PROPPATCH', $uri, new Headers(), $this->configureBody($properties));
    }

    /**
     * @param list<SimpleXMLElement>|SimpleXMLElement $properties
     */
    private function configureBody($properties): string
    {
        $xml = new SimpleXMLElement(<<<XML
<?xml version="1.0" encoding="utf-8"?>
<D:propertyupdate xmlns:D="DAV:">
<D:remove>
<D:prop>
</D:prop>
</D:remove>
</D:propertyupdate>
XML);

        $properties = \is_array($properties) ? $properties : [$properties];
        foreach ($properties as $property) {
            $namespaces = $property->getNamespaces();

            if (empty($namespaces)) {
                $xml->children('DAV:')->set->prop->addChild($property->getName())->asXML();
                continue;
            }

            \assert(\count($namespaces) == 1);
            $propertyNamespacePrefix = \array_key_first($namespaces);
            $propertyNamespaceUri = $namespaces[$propertyNamespacePrefix];
            $xml->children('DAV:')->set->prop->addChild($property->getName(), '', $propertyNamespaceUri);
        }

        $body = $xml->asXML();

        if ($body === false) {
            throw new InvalidArgumentException();
        }

        return $body;
    }
}
