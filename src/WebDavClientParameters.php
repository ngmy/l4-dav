<?php

declare(strict_types=1);

namespace Ngmy\L4Dav;

use Psr\Http\Message\UriInterface;

class WebDavClientParameters
{
    /** @var array<string, string> */
    private $defaultRequestHeaders = [];
    /** @var UriInterface|null */
    private $baseAddress;
    /** @var int|null */
    private $port;
    /** @var Credential|null */
    private $credential;

    /**
     * @param array<string, string> $defaultRequestHeaders
     * @return self
     */
    public function setDefaultRequestHeaders(array $defaultRequestHeaders): self
    {
        $this->defaultRequestHeaders = $defaultRequestHeaders;
        return $this;
    }

    /**
     * @param UriInterface $baseAddress
     * @return self
     */
    public function setBaseAddress(UriInterface $baseAddress): self
    {
        $this->baseAddress = $baseAddress;
        return $this;
    }

    /**
     * @param int $port
     * @return self
     */
    public function setPort(int $port): self
    {
        $this->port = $port;
        return $this;
    }

    /**
     * @param Credential $credential
     * @return self
     */
    public function setCredential(Credential $credential): self
    {
        $this->credential = $credential;
        return $this;
    }

    /**
     * @return array<string, string>
     */
    public function getDefaultRequestHeaders(): array
    {
        return $this->defaultRequestHeaders;
    }

    /**
     * @return UriInterface|null
     */
    public function getBaseAddress(): ?UriInterface
    {
        return $this->baseAddress;
    }

    /**
     * @return int|null
     */
    public function getPort(): ?int
    {
        return $this->port;
    }

    /**
     * @return Credential|null
     */
    public function getCredential(): ?Credential
    {
        return $this->credential;
    }
}
