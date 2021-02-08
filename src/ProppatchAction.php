<?php

declare(strict_types=1);

namespace Ngmy\L4Dav;

use InvalidArgumentException;

class ProppatchAction
{
    /** @var string */
    private $action;

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
        return $this->action;
    }

    private function validate(): void
    {
        if (!\in_array($this->action, ['set', 'remove'], true)) {
            throw new InvalidArgumentException(
                \sprintf('The PROPPATCH action must be "set" or "remove", "%s" given.', $this->action)
            );
        }
    }

    private function __construct(string $action)
    {
        $this->action = $action;
        $this->validate();
    }
}
