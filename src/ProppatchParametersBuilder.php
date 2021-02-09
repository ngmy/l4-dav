<?php

declare(strict_types=1);

namespace Ngmy\L4Dav;

use DOMNode;

class ProppatchParametersBuilder
{
    /** @var list<DOMNode> Properties to set */
    private $propertiesToSet = [];
    /** @var list<DOMNode> Properties to remove */
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
     * @return ProppatchParameters A new instance of a parameter class for the WebDAV PROPPATCH operation
     */
    public function build(): ProppatchParameters
    {
        return new ProppatchParameters($this->propertiesToSet, $this->propertiesToRemove);
    }
}
