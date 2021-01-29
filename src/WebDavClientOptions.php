<?php

declare(strict_types=1);

namespace Ngmy\L4Dav;

use Psr\Http\Message\UriInterface;

class WebDavClientOptions
{
    /** @var Headers */
    private $defaultRequestHeaders;
    /** @var UriInterface|null */
    private $baseUri;
    /** @var int|null */
    private $port;
    /** @var Credential|null */
    private $credential;

    /**
     * @return void
     */
    public function __construct()
    {
        $this->defaultRequestHeaders = new Headers();
    }

    public function setDefaultRequestHeaders(Headers $defaultRequestHeaders): self
    {
        $this->defaultRequestHeaders = $defaultRequestHeaders;
        return $this;
    }

    public function setBaseAddress(UriInterface $baseUri): self
    {
        $this->baseUri = $baseUri;
        return $this;
    }

    public function setPort(int $port): self
    {
        $this->port = $port;
        return $this;
    }

    public function setCredential(Credential $credential): self
    {
        $this->credential = $credential;
        return $this;
    }

    public function getDefaultRequestHeaders(): Headers
    {
        return $this->defaultRequestHeaders;
    }

    public function getBaseUri(): ?UriInterface
    {
        return $this->baseUri;
    }

    public function getPort(): ?int
    {
        return $this->port;
    }

    public function getCredential(): ?Credential
    {
        return $this->credential;
    }
}
