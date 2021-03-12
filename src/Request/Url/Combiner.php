<?php

declare(strict_types=1);

namespace Ngmy\WebDav\Request\Url;

use Ngmy\WebDav\Request;

class Combiner
{
    /** @var Request\Url\Base */
    private $baseUrl;
    /** @var Request\Url\Relative */
    private $relativeUrl;

    public function __construct(Request\Url\Base $baseUrl, Request\Url\Relative $relativeUrl)
    {
        $this->baseUrl = $baseUrl;
        $this->relativeUrl = $relativeUrl;
    }

    public function combine(): Request\Url\Full
    {
        return Request\Url::createFullUrl(
            $this->baseUrl->getUri()
                ->withPath($this->combinePath())
                ->withQuery($this->relativeUrl->getUri()->getQuery())
                ->withFragment($this->relativeUrl->getUri()->getFragment())
        );
    }

    private function combinePath(): string
    {
        $baseUrlPath = $this->baseUrl->getUri()->getPath();
        $relativeUrlPath = $this->relativeUrl->getUri()->getPath();
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
