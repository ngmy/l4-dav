<?php

declare(strict_types=1);

namespace Ngmy\PhpWebDav;

use InvalidArgumentException;

class Depth
{
    /** @var string */
    private $depth;

    /**
     * @param int|string $depth
     */
    public function __construct($depth = null)
    {
        $this->depth = \is_null($depth) ? 'infinity' : \strtolower((string) $depth);
        $this->validate();
    }

    public function __toString(): string
    {
        return $this->depth;
    }

    private function validate(): void
    {
        if (!\in_array($this->depth, ['0', '1', 'infinity'], true)) {
            throw new InvalidArgumentException(
                \sprintf('The depth must be "0", "1" or "infinity", "%s" given.', $this->depth)
            );
        }
    }
}
