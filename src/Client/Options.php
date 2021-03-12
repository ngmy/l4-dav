<?php

declare(strict_types=1);

namespace Ngmy\WebDav\Client;

use Ngmy\WebDav\Http;
use Ngmy\WebDav\Request;

class Options
{
    /** @var Request\Url\Base|null */
    private $baseUrl;
    /** @var Http\Client\Option\Port */
    private $port;
    /** @var Http\Client\Option\UserInfo */
    private $userInfo;
    /** @var Http\Client\Option\AuthType */
    private $authType;
    /** @var Request\Headers */
    private $defaultRequestHeaders;
    /** @var array<int, mixed> */
    private $defaultCurlOptions = [];

    /**
     * @param array<int, mixed> $defaultCurlOptions
     */
    public function __construct(
        ?Request\Url\Base $baseUrl,
        Http\Client\Option\Port $port,
        Http\Client\Option\UserInfo $userInfo,
        ?Http\Client\Option\AuthType $authType,
        Request\Headers $defaultRequestHeaders,
        array $defaultCurlOptions
    ) {
        $this->baseUrl = $baseUrl;
        $this->port = $port;
        $this->userInfo = $userInfo;
        $this->authType = $authType ?: Http\Client\Option\AuthType::NONE();
        $this->defaultRequestHeaders = $defaultRequestHeaders;
        $this->defaultCurlOptions = $defaultCurlOptions;
    }

    public function getBaseUrl(): ?Request\Url\Base
    {
        return $this->baseUrl;
    }

    public function getPort(): Http\Client\Option\Port
    {
        return $this->port;
    }

    public function getUserInfo(): Http\Client\Option\UserInfo
    {
        return $this->userInfo;
    }

    public function getAuthType(): Http\Client\Option\AuthType
    {
        return $this->authType;
    }

    public function getDefaultRequestHeaders(): Request\Headers
    {
        return $this->defaultRequestHeaders;
    }

    /**
     * @return array<int, mixed>
     */
    public function getDefaultCurlOptions(): array
    {
        return $this->defaultCurlOptions;
    }
}
