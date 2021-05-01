<?php

declare(strict_types=1);

namespace Ngmy\WebDav\Request\Parameters\Builder;

use LogicException;
use Ngmy\WebDav\Request;

use function is_null;

/**
 * @phpstan-import-type ConstructorType from Request\Parameters\Put as BuildingConstructorType
 *
 * @psalm-import-type ConstructorType from Request\Parameters\Put as BuildingConstructorType
 */
final class Put
{
    /**
     * @var callable
     * @phpstan-var BuildingConstructorType
     * @psalm-var BuildingConstructorType
     */
    private $buildingConstructor;
    /**
     * The source file path.
     *
     * @var string|null
     */
    private $sourcePath;

    /**
     * Create a new instance of the builder class.
     *
     * Call Request\Parameters\Put::createBuilder() instead of calling this constructor directly.
     *
     * @see Request\Parameters\Put::createBuilder()
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
     * Set the source file path.
     *
     * @param string $sourcePath The source file path
     * @return self The value of the calling object
     */
    public function setSourcePath(string $sourcePath): self
    {
        $new = clone $this;
        $new->sourcePath = $sourcePath;
        return $new;
    }

    /**
     * Build a new instance of a parameter class for the WebDAV PUT operation.
     *
     * @return Request\Parameters\Put A new instance of a parameter class for the WebDAV PUT operation
     */
    public function build(): Request\Parameters\Put
    {
        if (is_null($this->sourcePath)) {
            throw new LogicException('The source path must be provided.');
        }
        return ($this->buildingConstructor)(
            $this->sourcePath
        );
    }
}
