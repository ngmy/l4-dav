<?php

declare(strict_types=1);

namespace Ngmy\L4Dav;

use SimpleXMLElement;

class Hoge
{
    /** @var string */
    private $action;
    /** @var list<SimpleXMLElement> */
    private $properties;

    /**
     * @param list<SimpleXMLElement> $properties
     */
    public static function createSet(array $properties): self
    {
        return new self('set', $properties);
    }

    /**
     * @param list<SimpleXMLElement> $properties
     */
    public static function createRemove(array $properties): self
    {
        return new self('remove', $properties);
    }

    public function hoge(SimpleXMLElement $xml): void
    {
        if (!empty($this->properties)) {
            $xml->addChild($this->action, '', 'DAV:')->addChild('prop', '', 'DAV:');
        }

        foreach ($this->properties as $property) {
            $namespaces = $property->getNamespaces();

            if (empty($namespaces)) {
                $xml->children('DAV:')->{$this->action}->prop->addChild(
                    $property->getName(),
                    (string) $property
                )->asXML();
                continue;
            }

            \assert(\count($namespaces) == 1);
            $propertyNamespacePrefix = \array_key_first($namespaces);
            $propertyNamespaceUri = $namespaces[$propertyNamespacePrefix];
            $xml->children('DAV:')->{$this->action}->prop->addChild(
                $property->getName(),
                (string) $property,
                $propertyNamespaceUri
            );
        }
    }

    /**
     * @param list<SimpleXMLElement> $properties
     */
    private function __construct(string $action, array $properties)
    {
        $this->action = $action;
        $this->properties = $properties;
    }
}
