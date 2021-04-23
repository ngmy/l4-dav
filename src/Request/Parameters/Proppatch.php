<?php

declare(strict_types=1);

namespace Ngmy\WebDav\Request\Parameters;

use DOMNode;
use InvalidArgumentException;

class Proppatch
{
    /**
     * @var array<int, DOMNode>
     * @phpstan-var list<DOMNode>
     * @psalm-var list<DOMNode>
     */
    private $propertiesToSet = [];
    /**
     * @var array<int, DOMNode>
     * @phpstan-var list<DOMNode>
     * @psalm-var list<DOMNode>
     */
    private $propertiesToRemove = [];

    /**
     * @param array<int, DOMNode> $propertiesToSet
     * @param array<int, DOMNode> $propertiesToRemove
     *
     * @phpstan-param list<DOMNode> $propertiesToSet
     * @phpstan-param list<DOMNode> $propertiesToRemove
     *
     * @psalm-param list<DOMNode> $propertiesToSet
     * @psalm-param list<DOMNode> $propertiesToRemove
     */
    public function __construct($propertiesToSet = [], $propertiesToRemove = [])
    {
        $this->propertiesToSet = $propertiesToSet;
        $this->propertiesToRemove = $propertiesToRemove;
        $this->validate();
    }

    /**
     * @return array<int, DOMNode>
     *
     * @phpstan-return list<DOMNode>
     *
     * @psalm-return list<DOMNode>
     */
    public function getPropertiesToSet(): array
    {
        return $this->propertiesToSet;
    }

    /**
     * @return array<int, DOMNode>
     *
     * @phpstan-return list<DOMNode>
     *
     * @psalm-return list<DOMNode>
     */
    public function getPropertiesToRemove(): array
    {
        return $this->propertiesToRemove;
    }

    /**
     * @throws InvalidArgumentException
     */
    private function validate(): void
    {
        if (empty($this->propertiesToSet) && empty($this->propertiesToRemove)) {
            throw new InvalidArgumentException(
                'PROPPATCH parameters must add properties to set and/or remove.'
            );
        }
    }
}
