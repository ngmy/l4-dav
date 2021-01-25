<?php

declare(strict_types=1);

namespace Ngmy\L4Dav;

use InvalidArgumentException;

class Url
{
    /** @var string */
    private $url;

    /**
     * Create a new Client class object.
     *
     * @param string $url
     * @throws InvalidArgumentException
     * @return void
     */
    public function __construct(string $url)
    {
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            throw new InvalidArgumentException('Invalid URL format (' . $url . ')');
        }
        $this->url = $url;
    }

    /**
     * @return string
     */
    public function value(): string
    {
        return $this->url;
    }

    /**
     * @return self
     */
    public function withPath(string $path): self
    {
        return new self(rtrim($this->url, '/') . '/' . ltrim($path, '/'));
    }

    /**
     * @return self
     */
    public function withoutPort(): self
    {
        $parts = $this->parse();
        return new self($parts['scheme'] . '://' . $parts['host'] . $parts['path']);
    }

    /**
     * @return array<string, mixed>
     */
    public function parse(): array
    {
        $parts = parse_url($this->url);
        if ($parts === false) {
            throw new InvalidArgumentException('Invalid URL format (' . $this->url . ')');
        }
        return $parts;
    }
}
