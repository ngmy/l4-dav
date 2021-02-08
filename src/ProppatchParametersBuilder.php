<?php

declare(strict_types=1);

namespace Ngmy\L4Dav;

use DOMNode;

class ProppatchParametersBuilder
{
    /** @var list<DOMNode> */
    private $propertiesToSet = [];
    /** @var list<DOMNode> */
    private $propertiesToRemove = [];

    /**
     * @return $this The value of the calling object
     */
    public function addPropertyToSet(DOMNode $propertyToSet): self
    {
        $this->propertiesToSet[] = $propertyToSet;
        return $this;
    }

    /**
     * @return $this The value of the calling object
     */
    public function addPropertyToRemove(DOMNode $propertyToRemove): self
    {
        $this->propertiesToRemove[] = $propertyToRemove;
        return $this;
    }

    /**
     * Build a new instance of a parameter class for the WebDAV PROPPATCH method.
     *
     * @return ProppatchParameters A new instance of a parameter class for the WebDAV PROPPATCH method
     */
    public function build(): ProppatchParameters
    {
        return new ProppatchParameters($this->propertiesToSet, $this->propertiesToRemove);
    }
}
