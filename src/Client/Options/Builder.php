<?php

declare(strict_types=1);

namespace Ngmy\WebDav\Client\Options;

use Ngmy\WebDav\Client;
use Ngmy\WebDav\Request;
use Psr\Http\Message\UriInterface;

class Builder
{
    /**
     * The base URL of the WebDAV server.
     *
     * @var Request\Url\Base|null
     */
    private $baseUrl;
    /**
     * Default request headers.
     *
     * @var Request\Headers
     */
    private $defaultRequestHeaders;

    /**
     * Create a new instance of the WebDAV client options builder.
     */
    public function __construct()
    {
        $this->defaultRequestHeaders = new Request\Headers();
    }

    /**
     * Set the base URL of the WebDAV server.
     *
     * @param string|UriInterface $baseUrl The base URL of the WebDAV server
     * @return $this The value of the calling object
     */
    public function setBaseUrl($baseUrl): self
    {
        $this->baseUrl = Request\Url::createBaseUrl((string) $baseUrl);
        return $this;
    }

    /**
     * Set default request headers.
     *
     * @param array<string, string> $defaultRequestHeaders Default request headers
     * @return $this The value of the calling object
     */
    public function setDefaultRequestHeaders(array $defaultRequestHeaders): self
    {
        $this->defaultRequestHeaders = new Request\Headers($defaultRequestHeaders);
        return $this;
    }

    /**
     * Build WebDAV client options.
     *
     * @return Client\Options WebDAV client options
     */
    public function build(): Client\Options
    {
        return new Client\Options(
            $this->baseUrl,
            $this->defaultRequestHeaders
        );
    }
}
