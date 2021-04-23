<?php

declare(strict_types=1);

namespace Ngmy\WebDav\Request\Parameters\Builder;

use DOMNode;
use Ngmy\WebDav\Request;

class Proppatch
{
    /**
     * Properties to set.
     *
     * @var array<int, DOMNode>
     * @phpstan-var list<DOMNode>
     * @psalm-var list<DOMNode>
     */
    private $propertiesToSet = [];
    /**
     * Properties to remove.
     *
     * @var array<int, DOMNode>
     * @phpstan-var list<DOMNode>
     * @psalm-var list<DOMNode>
     */
    private $propertiesToRemove = [];

    /**
     * Add the property to set.
     *
     * @param DOMNode $propertyToSet The property to set
     * @return $this The value of the calling object
     */
    public function addPropertyToSet(DOMNode $propertyToSet): self
    {
        $this->propertiesToSet[] = $propertyToSet;
        return $this;
    }

    /**
     * Add the property to remove.
     *
     * @param DOMNode $propertyToRemove The property to remove
     * @return $this The value of the calling object
     */
    public function addPropertyToRemove(DOMNode $propertyToRemove): self
    {
        $this->propertiesToRemove[] = $propertyToRemove;
        return $this;
    }

    /**
     * Build a new instance of a parameter class for the WebDAV PROPPATCH operation.
     *
     * @return Request\Parameters\Proppatch A new instance of a parameter class for the WebDAV PROPPATCH operation
     */
    public function build(): Request\Parameters\Proppatch
    {
        return new Request\Parameters\Proppatch($this->propertiesToSet, $this->propertiesToRemove);
    }
}
