<?php

declare(strict_types=1);

namespace Ngmy\L4Dav;

use InvalidArgumentException;

class ShortcutUrl extends Url
{
    /**
     * @throws InvalidArgumentException
     */
    protected function validate(): void
    {
        if (!empty($this->uri->getScheme())) {
            throw new InvalidArgumentException(
                \sprintf('The shortcut URL `%s` must not contain scheme', $this->uri)
            );
        }
        if (!empty($this->uri->getAuthority())) {
            throw new InvalidArgumentException(
                \sprintf('The shortcut URL `%s` must not contain authority', $this->uri)
            );
        }
    }
}
