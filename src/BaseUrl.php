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
                \sprintf('The scheme of the base URL must be "http" or "https", "%s" given.', $this->uri)
            );
        }
        if (empty($this->uri->getAuthority())) {
            throw new InvalidArgumentException(
                \sprintf('The base URL must contain an authority, "%s" given.', $this->uri)
            );
        }
        if ($this->uri->getPath() != '' && $this->uri->getPath()[0] != '/') {
            throw new InvalidArgumentException(
                \sprintf('The path of the base URL must be empty or begin with a slash, "%s" given.', $this->uri)
            );
        }
        if (!empty($this->uri->getQuery())) {
            throw new InvalidArgumentException(
                \sprintf('The base URL must not contain a query, "%s" given.', $this->uri)
            );
        }
        if (!empty($this->uri->getFragment())) {
            throw new InvalidArgumentException(
                \sprintf('The base URL must not contain a fragment, "%s" given.', $this->uri)
            );
        }
    }
}
