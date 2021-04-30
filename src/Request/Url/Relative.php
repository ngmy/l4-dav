<?php

declare(strict_types=1);

namespace Ngmy\WebDav\Request\Url;

use InvalidArgumentException;
use Ngmy\WebDav\Request;

use function sprintf;

class Relative extends Request\Url
{
    protected function validate(): void
    {
        if (!empty($this->uri->getScheme())) {
            throw new InvalidArgumentException(
                sprintf(
                    'The shortcut URL "%s" must not contain a scheme, "%s" given.',
                    (string) $this->uri,
                    $this->uri->getScheme()
                )
            );
        }
        if (!empty($this->uri->getAuthority())) {
            throw new InvalidArgumentException(
                sprintf(
                    'The shortcut URL "%s" must not contain an authority, "%s" given.',
                    (string) $this->uri,
                    $this->uri->getAuthority()
                )
            );
        }
    }
}
