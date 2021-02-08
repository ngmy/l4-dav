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
                \sprintf(
                    'The scheme of the full URL "%s" must be "http" or "https", "%s" given.',
                    $this->uri,
                    $this->uri->getScheme()
                )
            );
        }
        if (empty($this->uri->getAuthority())) {
            throw new InvalidArgumentException(
                \sprintf(
                    'The full URL "%s" must contain an authority.',
                    $this->uri
                )
            );
        }
        if ($this->uri->getPath() != '' && $this->uri->getPath()[0] != '/') {
            throw new InvalidArgumentException(
                \sprintf(
                    'The path of the full URL "%s" must be empty or begin with a slash, "%s" given.',
                    $this->uri,
                    $this->uri->getpath()
                )
            );
        }
    }
}
