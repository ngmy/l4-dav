<?php

declare(strict_types=1);

namespace Ngmy\WebDav\Request;

use Psr\Http\Message\RequestInterface;
use Symfony\Component\HttpFoundation\HeaderBag;

class Headers
{
    /**
     * @var HeaderBag
     * @phpstan-var HeaderBag<string, list<string>|string>
     * @psalm-var HeaderBag
     */
    private $headers;

    /**
     * @param array<string, array<int, string>|string> $headers
     *
     * @phpstan-param array<string, list<string>|string> $headers
     *
     * @psalm-param array<string, list<string>|string> $headers
     */
    public function __construct(array $headers = [])
    {
        $this->headers = new HeaderBag($headers);
    }

    /**
     * @param array<int, string>|string $values
     *
     * @phpstan-param list<string>|string $values
     *
     * @psalm-param list<string>|string $values
     */
    public function withHeader(string $key, $values): self
    {
        $new = clone $this->headers;
        $new->set($key, $values);
        /** @psalm-var array<string, list<string>|string> $headers */
        $headers = $new->all();
        return new self($headers);
    }

    public function withHeaders(Headers $headers): self
    {
        $new = clone $this->headers;
        $new->add($headers->toArray());
        /** @psalm-var array<string, list<string>|string> $headers */
        $headers = $new->all();
        return new self($headers);
    }

    public function provide(RequestInterface $request): RequestInterface
    {
        /**
         * @psalm-var string              $key
         * @psalm-var list<string>|string $value
         */
        foreach ($this->headers as $key => $value) {
            $request = $request->withHeader($key, $value);
        }
        return $request;
    }

    /**
     * @return array<string, array<int, string>|string>
     *
     * @phpstan-return array<string, list<string>|string>
     *
     * @psalm-return array<string, list<string>|string>
     */
    public function toArray(): array
    {
        /** @psalm-var array<string, list<string>|string> */
        return $this->headers->all();
    }
}
