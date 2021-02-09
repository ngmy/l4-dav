<?php

declare(strict_types=1);

namespace Ngmy\L4Dav;

class UrlCombiner
{
    /** @var BaseUrl */
    private $baseUrl;
    /** @var RelativeUrl */
    private $relativeUrl;

    public function __construct(BaseUrl $baseUrl, RelativeUrl $relativeUrl)
    {
        $this->baseUrl = $baseUrl;
        $this->relativeUrl = $relativeUrl;
    }

    public function combine(): FullUrl
    {
        return Url::createFullUrl(
            $this->baseUrl->uri()
                ->withPath($this->combinePath())
                ->withQuery($this->relativeUrl->uri()->getQuery())
                ->withFragment($this->relativeUrl->uri()->getFragment())
        );
    }

    private function combinePath(): string
    {
        $baseUrlPath = $this->baseUrl->uri()->getPath();
        $relativeUrlPath = $this->relativeUrl->uri()->getPath();
        if (!$this->baseUrl->hasPath() && !$this->relativeUrl->hasPath()) {
            return '';
        } elseif ($this->baseUrl->hasPathWithTrailingSlash() && $this->relativeUrl->hasPathWithLeadingSlash()) {
            return \substr($baseUrlPath, 0, \strlen($baseUrlPath) - 1) . '/' . \substr($relativeUrlPath, 1);
        } elseif (!$this->baseUrl->hasPathWithTrailingSlash() && !$this->relativeUrl->hasPathWithLeadingSlash()) {
            return $baseUrlPath . '/' . $relativeUrlPath;
        } else {
            return $baseUrlPath . $relativeUrlPath;
        }
    }
}
