<?php

declare(strict_types=1);

namespace Ngmy\WebDav\Client\Options;

use Ngmy\WebDav\Client;
use Ngmy\WebDav\Request;
use Psr\Http\Message\UriInterface;

/**
 * @phpstan-import-type ConstructorType from Client\Options as BuildingConstructorType
 *
 * @psalm-import-type ConstructorType from Client\Options as BuildingConstructorType
 */
final class Builder
{
    /**
     * @var callable
     * @phpstan-var BuildingConstructorType
     * @psalm-var BuildingConstructorType
     */
    private $buildingConstructor;
    /**
     * The base URL of the WebDAV server.
     *
     * @var Request\Url\Base|null
     */
    private $baseUrl;
    /**
     * Default request headers.
     *
     * @var Request\Headers
     */
    private $defaultRequestHeaders;

    /**
     * Create a new instance of the WebDAV client options builder.
     *
     * Call Client\Options::createBuilder() instead of calling this constructor directly.
     *
     * @see Client\Options::createBuilder()
     *
     * @phpstan-param BuildingConstructorType $buildingConstructor
     *
     * @psalm-param BuildingConstructorType $buildingConstructor
     */
    public function __construct(callable $buildingConstructor)
    {
        $this->buildingConstructor = $buildingConstructor;
        $this->defaultRequestHeaders = new Request\Headers();
    }

    /**
     * Set the base URL of the WebDAV server.
     *
     * @param string|UriInterface $baseUrl The base URL of the WebDAV server
     * @return self The value of the calling object
     */
    public function setBaseUrl($baseUrl): self
    {
        $new = clone $this;
        $new->baseUrl = Request\Url::createBaseUrl((string) $baseUrl);
        return $new;
    }

    /**
     * Set default request headers.
     *
     * @param array<string, string> $defaultRequestHeaders Default request headers
     * @return self The value of the calling object
     */
    public function setDefaultRequestHeaders(array $defaultRequestHeaders): self
    {
        $new = clone $this;
        $new->defaultRequestHeaders = new Request\Headers($defaultRequestHeaders);
        return $new;
    }

    /**
     * Build WebDAV client options.
     *
     * @return Client\Options WebDAV client options
     */
    public function build(): Client\Options
    {
        return ($this->buildingConstructor)(
            $this->baseUrl,
            $this->defaultRequestHeaders
        );
    }
}
