<?php

declare(strict_types=1);

namespace Ngmy\WebDav\Request\Parameters;

use Ngmy\WebDav\Request;

use function func_get_args;

/**
 * @phpstan-type ConstructorType = callable((Request\Header\Depth|null)=): self
 *
 * @psalm-type ConstructorType = callable((Request\Header\Depth|null)=): self
 */
final class Propfind
{
    /** @var Request\Header\Depth */
    private $depth;

    /**
     * Create a new instance of the builder class.
     *
     * @return Builder\Propfind A new instance of the builder class
     */
    public static function createBuilder(): Builder\Propfind
    {
        return new Builder\Propfind(self::getConstructor());
    }

    public function getDepth(): Request\Header\Depth
    {
        return $this->depth;
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
     * @param Request\Header\Depth $depth What depth to apply
     */
    private function __construct(Request\Header\Depth $depth = null)
    {
        $this->depth = $depth ?? Request\Header\Depth::INFINITY();
    }
}
