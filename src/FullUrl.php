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
                \sprintf('The full URL `%s` is invalid : scheme must be http or https', $this->uri)
            );
        }
        if (empty($this->uri->getAuthority())) {
            throw new InvalidArgumentException(
                \sprintf('The full URL `%s` must contain authority', $this->uri)
            );
        }
        if ($this->uri->getPath() != '' && $this->uri->getPath()[0] != '/') {
            throw new InvalidArgumentException(
                \sprintf('The full URL `%s` is invalid : path must be empty or begin with a slash', $this->uri)
            );
        }
    }
}
