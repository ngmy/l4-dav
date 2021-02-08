<?php

declare(strict_types=1);

namespace Ngmy\L4Dav;

use DOMDocument;
use DOMNode;
use InvalidArgumentException;

class ProppatchRequestBodyBuilder
{
    /** @var DOMDocument */
    private $xml;
    /** @var list<DOMNode> */
    private $propetiesToSet;
    /** @var list<DOMNode> */
    private $propetiesToRemove;

    public function __construct()
    {
        $xml = new DOMDocument('1.0', 'utf-8');
        $xml->preserveWhiteSpace = false;
        $xml->formatOutput = true;
        $xml->appendChild($xml->createElementNS('DAV:', 'D:propertyupdate'));
        $this->xml = $xml;
    }

    /**
     * @return $this
     */
    public function addPropetyToSet(DOMNode $property): self
    {
        $this->propetiesToSet[] = $property;
        return $this;
    }

    /**
     * @return $this
     */
    public function addPropetyToRemove(DOMNode $property): self
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
            \assert(!\is_null($this->xml->getElementsByTagNameNS('DAV:', 'propertyupdate')->item(0)));
            $command->execute($this->xml->getElementsByTagNameNS('DAV:', 'propertyupdate')->item(0));
        }

        $body = $this->xml->saveXML();

        if ($body === false) {
            throw new InvalidArgumentException('Failed to build the PROPPATCH request body.');
        }

        return $body;
    }

    /**
     * @param list<DOMNode> $properties
     */
    private function configureCommand(ProppatchAction $action, array $properties): XmlCommandInterface
    {
        $addActionCommand = new AppendChildCommand(
            $this->xml->createElementNS('DAV:', \sprintf('D:%s', (string) $action))
        );
        $addPropCommand = new AppendChildCommand(
            $this->xml->createElementNS('DAV:', 'D:prop')
        );
        foreach ($properties as $property) {
            $addPropCommand->add(new AppendChildCommand(
                $this->xml->importNode($property, true)
            ));
        }
        $addActionCommand->add($addPropCommand);

        return $addActionCommand;
    }
}
