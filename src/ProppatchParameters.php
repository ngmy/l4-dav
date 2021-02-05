<?php

declare(strict_types=1);

namespace Ngmy\L4Dav;

use InvalidArgumentException;
use SimpleXMLElement;

class ProppatchParameters
{
    /** @var list<SimpleXMLElement> */
    private $propertiesToSet = [];
    /** @var list<SimpleXMLElement> */
    private $propertiesToRemove = [];

    /**
     * @param list<SimpleXMLElement> $propertiesToSet
     * @param list<SimpleXMLElement> $propertiesToRemove
     */
    public function __construct($propertiesToSet = [], $propertiesToRemove = [])
    {
        $this->propertiesToSet = $propertiesToSet;
        $this->propertiesToRemove = $propertiesToRemove;
        $this->validate();
    }

    /**
     * @return list<SimpleXMLElement>
     */
    public function propertiesToSet(): array
    {
        return $this->propertiesToSet;
    }

    /**
     * @return list<SimpleXMLElement>
     */
    public function propertiesToRemove(): array
    {
        return $this->propertiesToRemove;
    }

    /**
     * @throws InvalidArgumentException
     */
    private function validate(): void
    {
        if (empty($this->propertiesToSet) && empty($this->propertiesToRemove)) {
            throw new InvalidArgumentException();
        }
    }
}
