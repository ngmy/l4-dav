<?php

declare(strict_types=1);

namespace Ngmy\PhpWebDav;

use League\Uri\Contracts\PortInterface;
use League\Uri\Contracts\UserInfoInterface;

class WebDavClientOptions
{
    /** @var BaseUrl|null */
    private $baseUrl;
    /** @var PortInterface */
    private $port;
    /** @var UserInfoInterface */
    private $userInfo;
    /** @var Headers */
    private $defaultRequestHeaders;
    /** @var array<int, mixed> */
    private $defaultCurlOptions = [];

    /**
     * @param array<int, mixed> $defaultCurlOptions
     */
    public function __construct(
        ?BaseUrl $baseUrl,
        PortInterface $port,
        UserInfoInterface $userInfo,
        Headers $defaultRequestHeaders,
        array $defaultCurlOptions
    ) {
        $this->baseUrl = $baseUrl;
        $this->port = $port;
        $this->userInfo = $userInfo;
        $this->defaultRequestHeaders = $defaultRequestHeaders;
        $this->defaultCurlOptions = $defaultCurlOptions;
    }

    public function getBaseUrl(): ?BaseUrl
    {
        return $this->baseUrl;
    }

    public function getPort(): PortInterface
    {
        return $this->port;
    }

    public function getUserInfo(): UserInfoInterface
    {
        return $this->userInfo;
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
