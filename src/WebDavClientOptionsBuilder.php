<?php

declare(strict_types=1);

namespace Ngmy\L4Dav;

use League\Uri\Components\Port;
use League\Uri\Components\UserInfo;
use League\Uri\Contracts\PortInterface;
use League\Uri\Contracts\UserInfoInterface;
use Psr\Http\Message\UriInterface;

class WebDavClientOptionsBuilder
{
    /** @var BaseUrl|null Base URL */
    private $baseUrl;
    /** @var PortInterface Port */
    private $port;
    /** @var UserInfoInterface User info */
    private $userInfo;
    /** @var Headers Default HTTP request headers */
    private $defaultRequestHeaders;
    /** @var array<int, mixed> Default cURL options */
    private $defaultCurlOptions = [];

    /**
     * Create a new instance of the WebDAV client options builder.
     */
    public function __construct()
    {
        $this->port = new Port();
        $this->userInfo = new UserInfo();
        $this->defaultRequestHeaders = new Headers([]);
    }

    /**
     * Set base URL.
     *
     * @param string|UriInterface $baseUrl
     * @return $this The value of the calling object
     */
    public function baseUrl($baseUrl): self
    {
        $this->baseUrl = Url::createBaseUrl((string) $baseUrl);
        return $this;
    }

    /**
     * Set port.
     *
     * @return $this The value of the calling object
     */
    public function port(int $port): self
    {
        $this->port = new Port($port);
        return $this;
    }

    /**
     * Set password for authentication.
     *
     * @return $this The value of the calling object
     */
    public function userName(string $userName): self
    {
        $this->userInfo = $this->userInfo->withUserInfo($userName, $this->userInfo->getPass());
        return $this;
    }

    /**
     * Set password for authentication.
     *
     * @return $this The value of the calling object
     */
    public function password(string $password): self
    {
        $this->userInfo = $this->userInfo->withUserInfo($this->userInfo->getUser(), $password);
        return $this;
    }

    /**
     * Set default HTTP request headers.
     *
     * @param array<string, string> $defaultRequestHeaders
     * @return $this The value of the calling object
     */
    public function defaultRequestHeaders(array $defaultRequestHeaders): self
    {
        $this->defaultRequestHeaders = new Headers($defaultRequestHeaders);
        return $this;
    }

    /**
     * Set default cURL options.
     *
     * @param array<int, mixed> $defaultCurlOptions
     * @return $this The value of the calling object
     */
    public function defaultCurlOptions(array $defaultCurlOptions): self
    {
        $this->defaultCurlOptions = $defaultCurlOptions;
        return $this;
    }

    /**
     * Build WebDAV client options.
     */
    public function build(): WebDavClientOptions
    {
        return new WebDavClientOptions(
            $this->baseUrl,
            $this->port,
            $this->userInfo,
            $this->defaultRequestHeaders,
            $this->defaultCurlOptions
        );
    }
}
