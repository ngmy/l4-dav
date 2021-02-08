<?php

declare(strict_types=1);

namespace Ngmy\L4Dav;

use InvalidArgumentException;

class FullUrl extends Url
{
    /**
     * @throws InvalidArgumentException
     */
    protected function validate(): void
    {
        if (!\in_array($this->uri->getScheme(), ['http', 'https'])) {
            throw new InvalidArgumentException(
                \sprintf('Scheme of full URL must be "http" or "https", "%s" given.', $this->uri)
            );
        }
        if (empty($this->uri->getAuthority())) {
            throw new InvalidArgumentException(
                \sprintf('Full URL must contain authority, "%s" given.', $this->uri)
            );
        }
        if ($this->uri->getPath() != '' && $this->uri->getPath()[0] != '/') {
            throw new InvalidArgumentException(
                \sprintf('Path of full URL must be empty or begin with a slash, "%s" given.', $this->uri)
            );
        }
    }
}
