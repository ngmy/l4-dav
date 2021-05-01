<?php

declare(strict_types=1);

namespace Ngmy\WebDav\Request\Parameters;

use DOMNode;

use function func_get_args;

/**
 * @phpstan-type ConstructorType = callable(list<DOMNode>=, list<DOMNode>=): self
 *
 * FIXME: https://github.com/vimeo/psalm/issues/4866
 * @psalm-type ConstructorType = callable(list=, list=): self
 */
final class Proppatch
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
     * Create a new instance of the builder class.
     *
     * @return Builder\Proppatch A new instance of the builder class
     */
    public static function createBuilder(): Builder\Proppatch
    {
        return new Builder\Proppatch(self::getConstructor());
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
     * @param array<int, DOMNode> $propertiesToSet
     * @param array<int, DOMNode> $propertiesToRemove
     *
     * @phpstan-param list<DOMNode> $propertiesToSet
     * @phpstan-param list<DOMNode> $propertiesToRemove
     *
     * @psalm-param list<DOMNode> $propertiesToSet
     * @psalm-param list<DOMNode> $propertiesToRemove
     */
    private function __construct($propertiesToSet = [], $propertiesToRemove = [])
    {
        $this->propertiesToSet = $propertiesToSet;
        $this->propertiesToRemove = $propertiesToRemove;
    }
}
