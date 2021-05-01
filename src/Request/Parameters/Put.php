<?php

declare(strict_types=1);

namespace Ngmy\WebDav\Request\Parameters;

use function func_get_args;

/**
 * @phpstan-type ConstructorType = callable(string): self
 *
 * @psalm-type ConstructorType = callable(string): self
 */
final class Put
{
    /** @var string */
    private $sourcePath;

    /**
     * Create a new instance of the builder class.
     *
     * @return Builder\Put A new instance of the builder class
     */
    public static function createBuilder(): Builder\Put
    {
        return new Builder\Put(self::getConstructor());
    }

    public function getSourcePath(): string
    {
        return $this->sourcePath;
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
     * @param string $sourcePath The source path of a file
     */
    private function __construct(string $sourcePath)
    {
        $this->sourcePath = $sourcePath;
    }
}
