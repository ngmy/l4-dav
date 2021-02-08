<?php

declare(strict_types=1);

namespace Ngmy\L4Dav;

use DOMNode;
use InvalidArgumentException;

class ProppatchParameters
{
    /** @var list<DOMNode> */
    private $propertiesToSet = [];
    /** @var list<DOMNode> */
    private $propertiesToRemove = [];

    /**
     * @param list<DOMNode> $propertiesToSet
     * @param list<DOMNode> $propertiesToRemove
     */
    public function __construct($propertiesToSet = [], $propertiesToRemove = [])
    {
        $this->propertiesToSet = $propertiesToSet;
        $this->propertiesToRemove = $propertiesToRemove;
        $this->validate();
    }

    /**
     * @return list<DOMNode>
     */
    public function propertiesToSet(): array
    {
        return $this->propertiesToSet;
    }

    /**
     * @return list<DOMNode>
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
        if (empty($this->propertiesToSet) && empty($this->popertiesToRemove)) {
            throw new InvalidArgumentException('Either or both of the properties to set or the properties to remove.');
        }
    }
}
