<?php

declare(strict_types=1);

namespace Ngmy\PhpWebDav;

use InvalidArgumentException;

class RelativeUrl extends Url
{
    /**
     * @throws InvalidArgumentException
     */
    protected function validate(): void
    {
        if (!empty($this->uri->getScheme())) {
            throw new InvalidArgumentException(
                \sprintf(
                    'The shortcut URL "%s" must not contain a scheme, "%s" given.',
                    $this->uri,
                    $this->uri->getScheme()
                )
            );
        }
        if (!empty($this->uri->getAuthority())) {
            throw new InvalidArgumentException(
                \sprintf(
                    'The shortcut URL "%s" must not contain an authority, "%s" given.',
                    $this->uri,
                    $this->uri->getAuthority()
                )
            );
        }
    }
}
