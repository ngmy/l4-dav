<?php

declare(strict_types=1);

namespace Ngmy\L4Dav;

use League\Uri\Components\UserInfo;
use League\Uri\Contracts\UserInfoInterface;
use Psr\Http\Message\UriInterface;

class WebDavClientOptionsBuilder
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

    public function __construct()
    {
        $this->userInfo = new UserInfo();
        $this->defaultRequestHeaders = new Headers([]);
    }

    /**
     * @param string|UriInterface $baseUri
     * @return $this
     */
    public function baseUri($baseUri): self
    {
        $this->baseUri = new BaseUri((string) $baseUri);
        return $this;
    }

    /**
     * @return $this
     */
    public function port(int $port): self
    {
        $this->port = $port;
        return $this;
    }

    /**
     * @return $this
     */
    public function userName(string $userName): self
    {
        $this->userInfo = $this->userInfo->withUserInfo($userName, $this->userInfo->getPass());
        return $this;
    }

    /**
     * @return $this
     */
    public function password(string $password): self
    {
        $this->userInfo = $this->userInfo->withUserInfo($this->userInfo->getUser(), $password);
        return $this;
    }

    /**
     * @param array<string, string> $defaultRequestHeaders
     * @return $this
     */
    public function defaultRequestHeaders(array $defaultRequestHeaders): self
    {
        $this->defaultRequestHeaders = new Headers($defaultRequestHeaders);
        return $this;
    }

    /**
     * @param array<int, mixed> $defaultCurlOptions
     */
    public function defaultCurlOptions(array $defaultCurlOptions): self
    {
        $this->defaultCurlOptions = $defaultCurlOptions;
        return $this;
    }

    public function build(): WebDavClientOptions
    {
        return new WebDavClientOptions(
            $this->baseUri,
            $this->port,
            $this->userInfo,
            $this->defaultRequestHeaders,
            $this->defaultCurlOptions
        );
    }
}
