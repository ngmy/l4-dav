<?php

declare(strict_types=1);

namespace Ngmy\PhpWebDav;

use Symfony\Component\HttpFoundation\HeaderBag;

class Headers
{
    /** @var HeaderBag<string, list<string>|string> */
    private $headers;

    /**
     * @param array<string, list<string>|string> $headers
     */
    public function __construct(array $headers = [])
    {
        $this->headers = new HeaderBag($headers);
    }

    /**
     * @param list<string>|string $values
     */
    public function withHeader(string $key, $values): self
    {
        $new = clone $this->headers;
        $new->set($key, $values);
        return new self($new->all());
    }

    public function withHeaders(Headers $headers): self
    {
        $new = clone $this->headers;
        $new->add($headers->toArray());
        return new self($new->all());
    }

    /**
     * @return array<string, list<string>>
     */
    public function toArray(): array
    {
        return $this->headers->all();
    }
}
