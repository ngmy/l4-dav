<?php

declare(strict_types=1);

namespace Ngmy\L4Dav;

use InvalidArgumentException;

class ProppatchInstruction
{
    /** @var string */
    private $instruction;

    public static function createSet(): self
    {
        return new self('set');
    }

    public static function createRemove(): self
    {
        return new self('remove');
    }

    public function __toString(): string
    {
        return $this->instruction;
    }

    private function validate(): void
    {
        if (!\in_array($this->instruction, ['set', 'remove'], true)) {
            throw new InvalidArgumentException(
                \sprintf('The PROPPATCH instruction must be "set" or "remove", "%s" given.', $this->instruction)
            );
        }
    }

    private function __construct(string $instruction)
    {
        $this->instruction = $instruction;
        $this->validate();
    }
}
