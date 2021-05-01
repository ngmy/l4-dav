<?php

declare(strict_types=1);

namespace Ngmy\WebDav\Request\Parameters\Builder;

use DOMNode;
use LogicException;
use Ngmy\WebDav\Request;

/**
 * @phpstan-import-type ConstructorType from Request\Parameters\Proppatch as BuildingConstructorType
 *
 * @psalm-import-type ConstructorType from Request\Parameters\Proppatch as BuildingConstructorType
 */
final class Proppatch
{
    /**
     * @var callable
     * @phpstan-var BuildingConstructorType
     * @psalm-var BuildingConstructorType
     */
    private $buildingConstructor;
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
     * Create a new instance of the builder class.
     *
     * Call Request\Parameters\Proppatch::createBuilder() instead of calling this constructor directly.
     *
     * @see Request\Parameters\Proppatch::createBuilder()
     *
     * @phpstan-param BuildingConstructorType $buildingConstructor
     *
     * @psalm-param BuildingConstructorType $buildingConstructor
     */
    public function __construct(callable $buildingConstructor)
    {
        $this->buildingConstructor = $buildingConstructor;
    }

    /**
     * Add the property to set.
     *
     * @param DOMNode $propertyToSet The property to set
     * @return self The value of the calling object
     */
    public function addPropertyToSet(DOMNode $propertyToSet): self
    {
        $new = clone $this;
        $new->propertiesToSet[] = $propertyToSet;
        return $new;
    }

    /**
     * Add the property to remove.
     *
     * @param DOMNode $propertyToRemove The property to remove
     * @return self The value of the calling object
     */
    public function addPropertyToRemove(DOMNode $propertyToRemove): self
    {
        $new = clone $this;
        $new->propertiesToRemove[] = $propertyToRemove;
        return $new;
    }

    /**
     * Build a new instance of a parameter class for the WebDAV PROPPATCH operation.
     *
     * @return Request\Parameters\Proppatch A new instance of a parameter class for the WebDAV PROPPATCH operation
     */
    public function build(): Request\Parameters\Proppatch
    {
        if (empty($this->propertiesToSet) && empty($this->propertiesToRemove)) {
            throw new LogicException(
                'PROPPATCH parameters must add properties to set and/or remove.'
            );
        }
        return ($this->buildingConstructor)(
            $this->propertiesToSet,
            $this->propertiesToRemove
        );
    }
}
