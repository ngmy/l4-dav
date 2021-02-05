<?php

declare(strict_types=1);

namespace Ngmy\L4Dav;

use InvalidArgumentException;
use Psr\Http\Message\UriInterface;
use SimpleXMLElement;

class ProppatchCommand extends Command
{
    /**
     * @param string|UriInterface    $uri
     * @param list<SimpleXMLElement> $propertiesToSet
     * @param list<SimpleXMLElement> $propertiesToRemove
     */
    protected function __construct(WebDavClientOptions $options, $uri, array $propertiesToSet, array $propertiesToRemove)
    {
        parent::__construct($options, 'PROPPATCH', $uri, new Headers(), $this->configureBody(
            $propertiesToSet,
            $propertiesToRemove
        ));
    }

    /**
     * @param list<SimpleXMLElement> $propertiesToSet
     * @param list<SimpleXMLElement> $propertiesToRemove
     */
    private function configureBody(array $propertiesToSet, array $propertiesToRemove): string
    {
        $xml = new SimpleXMLElement(<<<XML
<?xml version="1.0" encoding="utf-8"?>
<D:propertyupdate xmlns:D="DAV:">
</D:propertyupdate>
XML);

        // TODO shared
        if (!empty($propertiesToSet)) {
            $xml->addChild('set', '', 'DAV:')->addChild('prop', '', 'DAV:');
        }

        foreach ($propertiesToSet as $propertyToSet) {
            $namespaces = $propertyToSet->getNamespaces();

            if (empty($namespaces)) {
                $xml->children('DAV:')->set->prop->addChild($propertyToSet->getName(), (string) $propertyToSet)->asXML();
                continue;
            }

            \assert(\count($namespaces) == 1);
            $propertyToSetNamespacePrefix = \array_key_first($namespaces);
            $propertyToSetNamespaceUri = $namespaces[$propertyToSetNamespacePrefix];
            $xml->children('DAV:')->set->prop->addChild($propertyToSet->getName(), (string) $propertyToSet, $propertyToSetNamespaceUri);
        }

        if (!empty($propertiesToRemove)) {
            $xml->addChild('remove', '', 'DAV:')->addChild('prop', '', 'DAV:');
        }

        foreach ($propertiesToRemove as $propertyToRemove) {
            $namespaces = $propertyToRemove->getNamespaces();

            if (empty($namespaces)) {
                $xml->children('DAV:')->remove->prop->addChild($propertyToRemove->getName(), (string) $propertyToRemove)->asXML();
                continue;
            }

            \assert(\count($namespaces) == 1);
            $propertyToRemoveNamespacePrefix = \array_key_first($namespaces);
            $propertyToRemoveNamespaceUri = $namespaces[$propertyToRemoveNamespacePrefix];
            $xml->children('DAV:')->remove->prop->addChild($propertyToRemove->getName(), (string) $propertyToRemove, $propertyToRemoveNamespaceUri);
        }

        $body = $xml->asXML();

        if ($body === false) {
            throw new InvalidArgumentException();
        }

        return $body;
    }
}
