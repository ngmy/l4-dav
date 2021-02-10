<?php

declare(strict_types=1);

namespace Ngmy\PhpWebDav;

use DOMNode;

class AppendChildCommand implements XmlCommandInterface
{
    /** @var DOMNode */
    private $child;
    /** @var list<XmlCommandInterface> */
    private $commands = [];

    public function __construct(DOMNode $child)
    {
        $this->child = $child;
    }

    public function execute(DOMNode $parent): void
    {
        $child = $parent->appendChild($this->child);

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
}
