<?php

declare(strict_types=1);

namespace Ngmy\WebDav\Request\Body\Builder;

use DOMDocument;
use DOMNode;
use Http\Discovery\Psr17FactoryDiscovery;
use InvalidArgumentException;
use Ngmy\WebDav\Request;

use function assert;
use function is_null;

class Proppatch
{
    /** @var DOMDocument */
    private $xml;
    /**
     * @var array<int, DOMNode>
     * @phpstan-var list<DOMNode>
     * @psalm-var list<DOMNode>
     */
    private $propetiesToSet = [];
    /**
     * @var array<int, DOMNode>
     * @phpstan-var list<DOMNode>
     * @psalm-var list<DOMNode>
     */
    private $propetiesToRemove = [];

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

    public function build(): Request\Body
    {
        $commands = [];

        if (!empty($this->propetiesToSet)) {
            $commands[] = $this->configureCommand(Instruction\Proppatch::SET(), $this->propetiesToSet);
        }
        if (!empty($this->propetiesToRemove)) {
            $commands[] = $this->configureCommand(Instruction\Proppatch::REMOVE(), $this->propetiesToRemove);
        }

        foreach ($commands as $command) {
            assert(!is_null($this->xml->getElementsByTagNameNS('DAV:', 'propertyupdate')->item(0)));
            $command->execute($this->xml->getElementsByTagNameNS('DAV:', 'propertyupdate')->item(0));
        }

        $body = $this->xml->saveXML();

        if ($body === false) {
            throw new InvalidArgumentException('Failed to build the PROPPATCH request body.');
        }

        return new Request\Body(Psr17FactoryDiscovery::findStreamFactory()->createStream($body));
    }

    /**
     * @param array<int, DOMNode> $properties
     *
     * @phpstan-param list<DOMNode> $properties
     *
     * @psalm-param list<DOMNode> $properties
     */
    private function configureCommand(Instruction\Proppatch $instruction, array $properties): XmlCommand
    {
        $addInstructionCommand = new XmlCommand\AppendChildCommand(
            $instruction->provide($this->xml)
        );
        $addPropCommand = new XmlCommand\AppendChildCommand(
            $this->xml->createElementNS('DAV:', 'D:prop')
        );
        foreach ($properties as $property) {
            $addPropCommand->add(new XmlCommand\AppendChildCommand(
                $this->xml->importNode($property, true)
            ));
        }
        $addInstructionCommand->add($addPropCommand);

        return $addInstructionCommand;
    }
}
