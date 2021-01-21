<?php

declare(strict_types=1);

namespace Ngmy\L4Dav;

interface RequestInterface
{
    /**
     * Set the HTTP method.
     *
     * @param string $method The HTTP method.
     * @return self Returns self for chainability.
     */
    public function method($method): self;
    /**
     * Set the request url.
     *
     * @param string $url The request url.
     * @return self Returns self for chainability.
     */
    public function url($url): self;
    /**
     * Set the HTTP headers.
     *
     * @param array<string, string> $headers The HTTP headers.
     * @return self Returns self for chainability.
     */
    public function headers(array $headers): self;
    /**
     * Set the cURL options.
     *
     * @param array<int, mixed> $options The cURL options.
     * @return self Returns self for chainability.
     */
    public function options(array $options): self;
    /**
     * Send the request.
     *
     * @return ResponseInterface Returns a Response class object.
     */
    public function send(): ResponseInterface;
}
