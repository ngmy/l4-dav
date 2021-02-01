<?php

declare(strict_types=1);

namespace Ngmy\L4Dav;

use InvalidArgumentException;
use Psr\Http\Message\UriInterface;

class BaseUrl extends Url
{
    /**
     * @param ShortcutUrl|string|UriInterface $shortcutUrl
     */
    public function uriWithShortcutUrl($shortcutUrl): UriInterface
    {
        return (new UrlCombiner($this, $shortcutUrl))->combine();
    }

    /**
     * @throws InvalidArgumentException
     */
    protected function validate(): void
    {
        if (!\in_array($this->uri->getScheme(), ['http', 'https'])) {
            throw new InvalidArgumentException(
                \sprintf('The base URL `%s` is invalid : scheme must be http or https', $this->uri)
            );
        }
        if (empty($this->uri->getAuthority())) {
            throw new InvalidArgumentException(
                \sprintf('The base URL `%s` must contain authority', $this->uri)
            );
        }
        if ($this->uri->getPath() != '' && $this->uri->getPath()[0] != '/') {
            throw new InvalidArgumentException(
                \sprintf('The base URL `%s` is invalid : path must be empty or begin with a slash', $this->uri)
            );
        }
        if (!empty($this->uri->getQuery())) {
            throw new InvalidArgumentException(
                \sprintf('The base URL `%s` must not contain query', $this->uri)
            );
        }
        if (!empty($this->uri->getFragment())) {
            throw new InvalidArgumentException(
                \sprintf('The base URL `%s` must not contain fragment', $this->uri)
            );
        }
    }
}
