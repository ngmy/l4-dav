<?php

declare(strict_types=1);

namespace Ngmy\WebDav\Request\Body\Builder\XmlCommand;

use DOMNode;
use Ngmy\WebDav\Request\Body\Builder\XmlCommand;

class AppendChildCommand implements XmlCommand
{
    /** @var DOMNode */
    private $child;
    /**
     * @var array<int, XmlCommand>
     * @phpstan-var list<XmlCommand>
     * @psalm-var list<XmlCommand>
     */
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
    public function add(XmlCommand $command): self
    {
        $this->commands[] = $command;
        return $this;
    }
}
