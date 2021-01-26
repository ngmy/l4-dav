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
    public function add(string $key, string $value): self
    {
        return new self(array_merge($this->headers, [$key => $value]));
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
