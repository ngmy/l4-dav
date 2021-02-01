<?php

declare(strict_types=1);

namespace Ngmy\L4Dav;

use Psr\Http\Message\UriInterface;

class UrlCombiner
{
    /** @var BaseUrl */
    private $baseUrl;
    /** @var ShortcutUrl */
    private $shortcutUrl;

    /**
     * @param BaseUrl|string|UriInterface     $baseUrl
     * @param ShortcutUrl|string|UriInterface $shortcutUrl
     */
    public function __construct($baseUrl, $shortcutUrl)
    {
        $this->baseUrl = new BaseUrl((string) $baseUrl);
        $this->shortcutUrl = new ShortcutUrl((string) $shortcutUrl);
    }

    public function combine(): UriInterface
    {
        return $this->baseUrl->uri()
            ->withPath($this->combinePath())
            ->withQuery($this->shortcutUrl->uri()->getQuery())
            ->withFragment($this->shortcutUrl->uri()->getFragment());
    }

    private function combinePath(): string
    {
        $baseUrlPath = $this->baseUrl->uri()->getPath();
        $shortcutUrlPath = $this->shortcutUrl->uri()->getPath();
        if (!$this->baseUrl->hasPath() && !$this->shortcutUrl->hasPath()) {
            return '';
        } elseif ($this->baseUrl->hasTrailingSlash() && $this->shortcutUrl->hasLeadingSlash()) {
            return \substr($baseUrlPath, 0, \strlen($baseUrlPath) - 1) . '/' . \substr($shortcutUrlPath, 1);
        } elseif (!$this->baseUrl->hasTrailingSlash() && !$this->shortcutUrl->hasLeadingSlash()) {
            return $baseUrlPath . '/' . $shortcutUrlPath;
        } else {
            return $baseUrlPath . $shortcutUrlPath;
        }
    }
}
