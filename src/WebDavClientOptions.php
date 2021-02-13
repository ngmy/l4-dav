<?php

declare(strict_types=1);

namespace Ngmy\PhpWebDav;

class WebDavClientOptions
{
    /** @var BaseUrl|null */
    private $baseUrl;
    /** @var Port */
    private $port;
    /** @var UserInfo */
    private $userInfo;
    /** @var AuthType */
    private $authType;
    /** @var Headers */
    private $defaultRequestHeaders;
    /** @var array<int, mixed> */
    private $defaultCurlOptions = [];

    /**
     * @param array<int, mixed> $defaultCurlOptions
     */
    public function __construct(
        ?BaseUrl $baseUrl,
        Port $port,
        UserInfo $userInfo,
        ?AuthType $authType,
        Headers $defaultRequestHeaders,
        array $defaultCurlOptions
    ) {
        $this->baseUrl = $baseUrl;
        $this->port = $port;
        $this->userInfo = $userInfo;
        $this->authType = $authType ?: AuthType::createNoneAuthtype();
        $this->defaultRequestHeaders = $defaultRequestHeaders;
        $this->defaultCurlOptions = $defaultCurlOptions;
    }

    public function getBaseUrl(): ?BaseUrl
    {
        return $this->baseUrl;
    }

    public function getPort(): Port
    {
        return $this->port;
    }

    public function getUserInfo(): UserInfo
    {
        return $this->userInfo;
    }

    public function getAuthType(): AuthType
    {
        return $this->authType;
    }

    public function getDefaultRequestHeaders(): Headers
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
