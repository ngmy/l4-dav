<?php

declare(strict_types=1);

namespace Ngmy\WebDav\Request\Parameters;

use Psr\Http\Message\UriInterface;

use function func_get_args;

/**
 * @phpstan-type ConstructorType = callable(UriInterface): self
 *
 * @psalm-type ConstructorType = callable(UriInterface): self
 */
final class Move
{
    /**
     * The destination resource URL.
     *
     * @var UriInterface
     */
    private $destinationUrl;

    /**
     * Create a new instance of the builder class.
     *
     * @return Builder\Move A new instance of the builder class
     */
    public static function createBuilder(): Builder\Move
    {
        return new Builder\Move(self::getConstructor());
    }

    public function getDestinationUrl(): UriInterface
    {
        return $this->destinationUrl;
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

    /**
     * @param UriInterface $destinationUrl The destination path of a file
     */
    private function __construct(UriInterface $destinationUrl)
    {
        $this->destinationUrl = $destinationUrl;
    }
}
