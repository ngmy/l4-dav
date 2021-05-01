<?php

declare(strict_types=1);

namespace Ngmy\WebDav\Request\Parameters\Builder;

use Ngmy\WebDav\Request;

/**
 * @phpstan-import-type ConstructorType from Request\Parameters\Propfind as BuildingConstructorType
 *
 * @psalm-import-type ConstructorType from Request\Parameters\Propfind as BuildingConstructorType
 */
final class Propfind
{
    /**
     * @var callable
     * @phpstan-var BuildingConstructorType
     * @psalm-var BuildingConstructorType
     */
    private $buildingConstructor;
    /**
     * What depth to apply.
     *
     * @var Request\Header\Depth|null
     */
    private $depth;

    /**
     * Create a new instance of the builder class.
     *
     * Call Request\Parameters\Propfind::createBuilder() instead of calling this constructor directly.
     *
     * @see Request\Parameters\Propfind::createBuilder()
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
     * Set what depth to apply.
     *
     * @param int|string $depth What depth to apply
     * @return self The value of the calling object
     */
    public function setDepth($depth): self
    {
        $new = clone $this;
        $new->depth = Request\Header\Depth::getInstance((string) $depth);
        return $new;
    }

    /**
     * Build a new instance of a parameter class for the WebDAV PROPFIND operation.
     *
     * @return Request\Parameters\Propfind A new instance of a parameter class for the WebDAV PROPFIND operation
     */
    public function build(): Request\Parameters\Propfind
    {
        return ($this->buildingConstructor)(
            $this->depth
        );
    }
}
