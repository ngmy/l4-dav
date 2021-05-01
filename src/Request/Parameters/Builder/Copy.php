<?php

declare(strict_types=1);

namespace Ngmy\WebDav\Request\Parameters\Builder;

use Http\Discovery\Psr17FactoryDiscovery;
use LogicException;
use Ngmy\WebDav\Request;
use Psr\Http\Message\UriInterface;

use function is_null;

/**
 * @phpstan-import-type ConstructorType from Request\Parameters\Copy as BuildingConstructorType
 *
 * @psalm-import-type ConstructorType from Request\Parameters\Copy as BuildingConstructorType
 */
final class Copy
{
    /**
     * @var callable
     * @phpstan-var BuildingConstructorType
     * @psalm-var BuildingConstructorType
     */
    private $buildingConstructor;
    /**
     * The destination resource URL.
     *
     * @var UriInterface|null
     */
    private $destinationUrl;
    /**
     * Whether to overwrite the resource if it exists.
     *
     * @var Request\Header\Overwrite|null
     */
    private $overwrite;

    /**
     * Create a new instance of the builder class.
     *
     * Call Request\Parameters\Copy::createBuilder() instead of calling this constructor directly.
     *
     * @see Request\Parameters\Copy::createBuilder()
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
     * Set the destination resource URL.
     *
     * @param string|UriInterface $destinationUrl The destination resource URL
     * @return self The value of the calling object
     */
    public function setDestinationUrl($destinationUrl): self
    {
        $new = clone $this;
        $new->destinationUrl = Psr17FactoryDiscovery::findUriFactory()->createUri((string) $destinationUrl);
        return $new;
    }

    /**
     * Set whether to overwrite the resource if it exists.
     *
     * @param bool $overwrite Whether to overwrite the resource if it exists
     * @return self The value of the calling object
     */
    public function setOverwrite(bool $overwrite): self
    {
        $new = clone $this;
        $new->overwrite = Request\Header\Overwrite::getInstance($overwrite);
        return $new;
    }

    /**
     * Build a new instance of a parameter class for the WebDAV COPY operation.
     *
     * @return Request\Parameters\Copy A new instance of a parameter class for the WebDAV COPY operation
     */
    public function build(): Request\Parameters\Copy
    {
        if (is_null($this->destinationUrl)) {
            throw new LogicException('The destination URL must be provided.');
        }
        return ($this->buildingConstructor)(
            $this->destinationUrl,
            $this->overwrite
        );
    }
}
