<?php

declare(strict_types=1);

namespace Ngmy\L4Dav;

class Headers
{
    /** @var array<string, string> */
    private $headers = [];

    /**
     * @param array<string, string> $headers
     * @return void
     */
    public function __construct(array $headers = [])
    {
        $this->headers = $headers;
    }

    /**
     * @param string $key
     * @param string $value
     * @return self
     */
    public function addHeader(string $key, string $value): self
    {
        return new self(array_merge($this->headers, [$key => $value]));
    }

    /**
     * @param Headers $that
     * @return self
     */
    public function addHeaders(Headers $that): self
    {
        return new self(array_merge($this->headers, $that->headers));
    }

    /**
     * @return array<string, string>
     */
    public function toArray(): array
    {
        return $this->headers;
    }

    /**
     * @param object $interest
     * @return void
     */
    public function provide($interest): void
    {
        foreach ($this->headers as $key => $value) {
            $interest->setHeader($key, $value);
        }
    }
}
