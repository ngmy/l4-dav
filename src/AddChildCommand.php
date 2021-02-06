<?php

declare(strict_types=1);

namespace Ngmy\L4Dav;

use SimpleXMLElement;

class AddChildCommand implements XmlCommandInterface
{
    /** @var SimpleXMLElement */
    private $child;
    /** @var list<XmlCommandInterface> */
    private $commands = [];

    public function __construct(SimpleXMLElement $child)
    {
        $this->child = $child;
    }

    public function execute(SimpleXMLElement $parent): void
    {
        $child = $this->addChild($parent);

        foreach ($this->commands as $command) {
            $command->execute($child);
        }
    }

    /**
     * @return $this
     */
    public function add(XmlCommandInterface $command): self
    {
        $this->commands[] = $command;
        return $this;
    }

    private function addChild(SimpleXMLElement $parent): SimpleXMLElement
    {
        $childNamespaces = $this->child->getNamespaces();

        if (empty($childNamespaces)) {
            return $parent->addChild($this->child->getName(), (string) $this->child);
        }

        \assert(\count($childNamespaces) == 1);
        $childNamespacePrefix = \array_key_first($childNamespaces);
        $childNamespaceUri = $childNamespaces[$childNamespacePrefix];
        return $parent->addChild($this->child->getName(), (string) $this->child, $childNamespaceUri);
    }
}
