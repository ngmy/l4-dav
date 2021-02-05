<?php

declare(strict_types=1);

namespace Ngmy\L4Dav;

use SimpleXMLElement;

class ProppatchParametersBuilder
{
    /** @var list<SimpleXMLElement> */
    private $propertiesToSet = [];
    /** @var list<SimpleXMLElement> */
    private $propertiesToRemove = [];

    /**
     * @param SimpleXMLElement $propertyToSet
     * @return $this
     */
    public function addPropertyToSet($propertyToSet): self
    {
        $this->propertiesToSet[] = $propertyToSet;
        return $this;
    }

    /**
     * @param SimpleXMLElement $propertyToRemove
     * @return $this
     */
    public function addPropertyToRemove($propertyToRemove): self
    {
        $this->propertiesToRemove[] = $propertyToRemove;
        return $this;
    }

    /**
     * Build WebDAV client options.
     */
    public function build(): ProppatchParameters
    {
        return new ProppatchParameters(
            $this->propertiesToSet,
            $this->propertiesToRemove
        );
    }
}
