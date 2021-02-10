<?php

declare(strict_types=1);

namespace Ngmy\PhpWebDav;

use InvalidArgumentException;

class BaseUrl extends Url
{
    public function createFullUrlWithRelativeUrl(RelativeUrl $relativeUrl): FullUrl
    {
        return (new UrlCombiner($this, $relativeUrl))->combine();
    }

    /**
     * @throws InvalidArgumentException
     */
    protected function validate(): void
    {
        if (!\in_array($this->uri->getScheme(), ['http', 'https'])) {
            throw new InvalidArgumentException(\sprintf(
                'The scheme of the base URL "%s" must be "http" or "https", "%s" given.',
                $this->uri,
                $this->uri->getScheme()
            ));
        }
        if (empty($this->uri->getAuthority())) {
            throw new InvalidArgumentException(
                \sprintf(
                    'The base URL "%s" must contain an authority.',
                    $this->uri
                )
            );
        }
        if ($this->uri->getPath() != '' && $this->uri->getPath()[0] != '/') {
            throw new InvalidArgumentException(
                \sprintf(
                    'The path of the base URL "%s" must be empty or begin with a slash, "%s" given.',
                    $this->uri,
                    $this->uri->getPath()
                )
            );
        }
        if (!empty($this->uri->getQuery())) {
            throw new InvalidArgumentException(
                \sprintf(
                    'The base URL "%s" must not contain a query, "%s" given.',
                    $this->uri,
                    $this->uri->getQuery()
                )
            );
        }
        if (!empty($this->uri->getFragment())) {
            throw new InvalidArgumentException(
                \sprintf(
                    'The base URL "%s" must not contain a fragment, "%s" given.',
                    $this->uri,
                    $this->uri->getFragment()
                )
            );
        }
    }
}
