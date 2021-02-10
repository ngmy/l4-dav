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
    /**
     * The base URL of the WebDAV server.
     *
     * @var BaseUrl|null
     */
    private $baseUrl;
    /**
     * The port of the WebDAV server.
     *
     * @var PortInterface
     */
    private $port;
    /**
     * The User info.
     *
     * @var UserInfoInterface
     */
    private $userInfo;
    /**
     * Default request headers.
     *
     * @var Headers
     */
    private $defaultRequestHeaders;
    /**
     * Default cURL options.
     *
     * @var array<int, mixed>
     */
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
     * Set the base URL of the WebDAV server.
     *
     * @param string|UriInterface $baseUrl The base URL of the WebDAV server
     * @return $this The value of the calling object
     */
    public function baseUrl($baseUrl): self
    {
        $this->baseUrl = Url::createBaseUrl((string) $baseUrl);
        return $this;
    }

    /**
     * Set the port number of the WebDAV server.
     *
     * @param int $port The port number of ther WebDAV server
     * @return $this The value of the calling object
     */
    public function port(int $port): self
    {
        $this->port = new Port($port);
        return $this;
    }

    /**
     * Set the username for authentication.
     *
     * @param string $userName The username for authentication
     * @return $this The value of the calling object
     */
    public function userName(string $userName): self
    {
        $this->userInfo = $this->userInfo->withUserInfo($userName, $this->userInfo->getPass());
        return $this;
    }

    /**
     * Set the password for authentication.
     *
     * @param string $password The password for authentication
     * @return $this The value of the calling object
     */
    public function password(string $password): self
    {
        $this->userInfo = $this->userInfo->withUserInfo($this->userInfo->getUser(), $password);
        return $this;
    }

    /**
     * Set default request headers.
     *
     * @param array<string, string> $defaultRequestHeaders Default request headers
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
     * @param array<int, mixed> $defaultCurlOptions Default cURL options
     * @return $this The value of the calling object
     */
    public function defaultCurlOptions(array $defaultCurlOptions): self
    {
        $this->defaultCurlOptions = $defaultCurlOptions;
        return $this;
    }

    /**
     * Build WebDAV client options.
     *
     * @return WebDavClientOptions WebDAV client options
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
