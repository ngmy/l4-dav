<?php

declare(strict_types=1);

namespace Ngmy\L4Dav;

use InvalidArgumentException;
use SimpleXMLElement;

class ProppatchRequestBodyBuilder
{
    /** @var SimpleXMLElement */
    private $xml;
    /** @var list<SimpleXMLElement> */
    private $propetiesToSet;
    /** @var list<SimpleXMLElement> */
    private $propetiesToRemove;

    public function __construct()
    {
        $this->xml = new SimpleXMLElement(<<<XML
<?xml version="1.0" encoding="utf-8"?>
<D:propertyupdate xmlns:D="DAV:">
</D:propertyupdate>
XML);
    }

    /**
     * @return $this
     */
    public function addPropetyToSet(SimpleXMLElement $property): self
    {
        $this->propetiesToSet[] = $property;
        return $this;
    }

    /**
     * @return $this
     */
    public function addPropetyToRemove(SimpleXMLElement $property): self
    {
        $this->propetiesToRemove[] = $property;
        return $this;
    }

    public function build(): string
    {
        $commands = [];

        if (!empty($this->propetiesToSet)) {
            $commands[] = $this->configureCommand(ProppatchAction::createSet(), $this->propetiesToSet);
        }
        if (!empty($this->propetiesToRemove)) {
            $commands[] = $this->configureCommand(ProppatchAction::createRemove(), $this->propetiesToRemove);
        }

        foreach ($commands as $command) {
            $command->execute($this->xml);
        }

        $body = $this->xml->asXML();

        if ($body === false) {
            throw new InvalidArgumentException();
        }

        return $body;
    }

    /**
     * @param list<SimpleXMLElement> $properties
     */
    private function configureCommand(ProppatchAction $action, array $properties): XmlCommandInterface
    {
        $addActionCommand = new AddChildCommand(
            new SimpleXMLElement(\sprintf('<%s/>', (string) $action), 0, false, 'DAV:')
        );
        $addPropCommand = new AddChildCommand(
            new SimpleXMLElement('<prop/>', 0, false, 'DAV:')
        );
        foreach ($properties as $property) {
            $addPropCommand->add(new AddChildCommand($property));
        }
        $addActionCommand->add($addPropCommand);

        return $addActionCommand;
    }
}
