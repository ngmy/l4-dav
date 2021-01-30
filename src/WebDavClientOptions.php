<?php

declare(strict_types=1);

namespace Ngmy\L4Dav;

use League\Uri\Contracts\UserInfoInterface;

class WebDavClientOptions
{
    /** @var BaseUri|null */
    private $baseUri;
    /** @var int|null */
    private $port;
    /** @var UserInfoInterface */
    private $userInfo;
    /** @var Headers */
    private $defaultRequestHeaders;
    /** @var array<int, mixed> */
    private $defaultCurlOptions = [];

    /**
     * @param array<int, mixed> $defaultCurlOptions
     * @return void
     */
    public function __construct(
        ?BaseUri $baseUri,
        ?int $port,
        UserInfoInterface $userInfo,
        Headers $defaultRequestHeaders,
        array $defaultCurlOptions
    ) {
        $this->baseUri = $baseUri;
        $this->port = $port;
        $this->userInfo = $userInfo;
        $this->defaultRequestHeaders = $defaultRequestHeaders;
        $this->defaultCurlOptions = $defaultCurlOptions;
    }

    public function baseUri(): ?BaseUri
    {
        return $this->baseUri;
    }

    public function port(): ?int
    {
        return $this->port;
    }

    public function userInfo(): UserInfoInterface
    {
        return $this->userInfo;
    }

    public function defaultRequestHeaders(): Headers
    {
        return $this->defaultRequestHeaders;
    }

    /**
     * @return array<int, mixed>
     */
    public function defaultCurlOptions(): array
    {
        return $this->defaultCurlOptions;
    }
}
