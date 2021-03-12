<?php

declare(strict_types=1);

namespace Ngmy\WebDav\Client\Options;

use Ngmy\WebDav\Client;
use Ngmy\WebDav\Http;
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
     * The port of the WebDAV server.
     *
     * @var Http\Client\Option\Port
     */
    private $port;
    /**
     * The User info.
     *
     * @var Http\Client\Option\UserInfo
     */
    private $userInfo;
    /**
     * The type of the authentication.
     *
     * @var Http\Client\Option\AuthType|null
     */
    private $authType;
    /**
     * Default request headers.
     *
     * @var Request\Headers
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
        $this->port = Http\Client\Option\Port::createFromNumber();
        $this->userInfo = Http\Client\Option\UserInfo::createFromUserNameAndPassword();
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
     * Set the port number of the WebDAV server.
     *
     * @param int $port The port number of ther WebDAV server
     * @return $this The value of the calling object
     */
    public function setPort(int $port): self
    {
        $this->port = Http\Client\Option\Port::createFromNumber($port);
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
        $this->authType = $this->authType ?: Http\Client\Option\AuthType::BASIC();
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
        $this->authType = $this->authType ?: Http\Client\Option\AuthType::BASIC();
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
        $this->authType = Http\Client\Option\AuthType::valueOf(\strtoupper($authType));
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
     * @return Client\Options WebDAV client options
     */
    public function build(): Client\Options
    {
        return new Client\Options(
            $this->baseUrl,
            $this->port,
            $this->userInfo,
            $this->authType,
            $this->defaultRequestHeaders,
            $this->defaultCurlOptions
        );
    }
}
