<?php

declare(strict_types=1);

namespace Ngmy\WebDav\Client;

use Ngmy\WebDav\Request;

use function func_get_args;

/**
 * @phpstan-type ConstructorType = callable(Request\Url\Base|null, Request\Headers): self
 *
 * @psalm-type ConstructorType = callable(Request\Url\Base|null, Request\Headers): self
 */
final class Options
{
    /** @var Request\Url\Base|null */
    private $baseUrl;
    /** @var Request\Headers */
    private $defaultRequestHeaders;

    /**
     * Create a new instance of the builder class.
     *
     * @return Options\Builder A new instance of the builder class
     */
    public static function createBuilder(): Options\Builder
    {
        return new Options\Builder(self::getConstructor());
    }

    public function getBaseUrl(): ?Request\Url\Base
    {
        return $this->baseUrl;
    }

    public function getDefaultRequestHeaders(): Request\Headers
    {
        return $this->defaultRequestHeaders;
    }

    /**
     * @phpstan-return ConstructorType
     *
     * @psalm-return ConstructorType
     */
    private static function getConstructor(): callable
    {
        return function (): self {
            /** @psalm-suppress MixedArgument */
            return new self(...func_get_args());
        };
    }

    private function __construct(
        ?Request\Url\Base $baseUrl,
        Request\Headers $defaultRequestHeaders
    ) {
        $this->baseUrl = $baseUrl;
        $this->defaultRequestHeaders = $defaultRequestHeaders;
    }
}
