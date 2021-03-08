<?php

declare(strict_types=1);

namespace Ngmy\PhpWebDav;

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
     * @var Port
     */
    private $port;
    /**
     * The User info.
     *
     * @var UserInfo
     */
    private $userInfo;
    /**
     * The type of the authentication.
     *
     * @var AuthType|null
     */
    private $authType;
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
        $this->port = Port::createFromNumber();
        $this->userInfo = UserInfo::createFromUserNameAndPassword();
        $this->defaultRequestHeaders = new Headers();
    }

    /**
     * Set the base URL of the WebDAV server.
     *
     * @param string|UriInterface $baseUrl The base URL of the WebDAV server
     * @return $this The value of the calling object
     */
    public function setBaseUrl($baseUrl): self
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
    public function setPort(int $port): self
    {
        $this->port = Port::createFromNumber($port);
        return $this;
    }

    /**
     * Set the username for authentication.
     *
     * @param string $userName The username for authentication
     * @return $this The value of the calling object
     */
    public function setUserName(string $userName): self
    {
        $this->userInfo = $this->userInfo->withUserNameAndPassword($userName, $this->userInfo->getPassword());
        $this->authType = $this->authType ?: AuthType::BASIC();
        return $this;
    }

    /**
     * Set the password for authentication.
     *
     * @param string $password The password for authentication
     * @return $this The value of the calling object
     */
    public function setPassword(string $password): self
    {
        $this->userInfo = $this->userInfo->withUserNameAndPassword($this->userInfo->getUserName(), $password);
        $this->authType = $this->authType ?: AuthType::BASIC();
        return $this;
    }

    /**
     * Set the type of authentication.
     *
     * @param string $authType The type of authentication
     * @return $this The value of the calling object
     */
    public function setAuthType(string $authType): self
    {
        $this->authType = AuthType::valueOf(\strtoupper($authType));
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
        $this->defaultRequestHeaders = new Headers($defaultRequestHeaders);
        return $this;
    }

    /**
     * Set default cURL options.
     *
     * @param array<int, mixed> $defaultCurlOptions Default cURL options
     * @return $this The value of the calling object
     */
    public function setDefaultCurlOptions(array $defaultCurlOptions): self
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
            $this->authType,
            $this->defaultRequestHeaders,
            $this->defaultCurlOptions
        );
    }
}
