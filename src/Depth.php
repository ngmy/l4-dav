<?php

declare(strict_types=1);

namespace Ngmy\L4Dav;

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
        $this->depth = is_null($depth) ? 'infinity' : (string) $depth;
        $this->validate();
    }

    public function __toString(): string
    {
        return $this->depth;
    }

    protected function validate(): void
    {
        if (!in_array($this->depth, ['0', '1', 'infinity'], true)) {
            throw new InvalidArgumentException(
                \sprintf('The depth `%s` must be "0" or "1" or "infinity"', $this->depth)
            );
        }
    }
}
