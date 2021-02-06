<?php

declare(strict_types=1);

namespace Ngmy\L4Dav;

use InvalidArgumentException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;

abstract class WebDavCommand
{
    /** @var string */
    protected $method;
    /** @var FullUrl */
    protected $url;
    /** @var WebDavClientOptions */
    protected $options;
    /** @var Headers */
    protected $headers;
    /** @var resource|StreamInterface|string|null */
    protected $body;
    /** @var WebDavCommandDispatcher */
    protected $dispatcher;
    /** @var ResponseInterface */
    protected $response;

    /**
     * @param list<mixed> ...$args
     */
    public static function create(string $command, ...$args): self
    {
        $class = '\\Ngmy\\L4Dav\\' . \ucfirst(\strtolower($command)) . 'Command';
        if (!\class_exists($class)) {
            throw new InvalidArgumentException(\sprintf('Class `%s` could not be instantiated', $class));
        }
        return new $class(...$args);
    }

    public function execute(): void
    {
        $this->doBefore();
        $this->dispatch();
        $this->doAfter();
    }

    public function getResult(): ResponseInterface
    {
        return $this->response;
    }

    public function method(): string
    {
        return $this->method;
    }

    public function url(): FullUrl
    {
        return $this->url;
    }

    public function options(): WebDavClientOptions
    {
        return $this->options;
    }

    public function headers(): Headers
    {
        return $this->headers;
    }

    /**
     * @return resource|StreamInterface|string|null
     */
    public function body()
    {
        return $this->body;
    }

    /**
     * @param string|UriInterface                  $url
     * @param Headers                              $headers
     * @param resource|StreamInterface|string|null $body
     */
    protected function __construct(
        string $method,
        $url,
        WebDavClientOptions $options,
        Headers $headers = null,
        $body = null
    ) {
        $this->method = $method;
        $this->url = Url::createFullUrl($url, $options->baseUrl());
        $this->options = $options;
        $this->headers = $headers ?: new Headers([]);
        $this->body = $body;
        $this->dispatcher = new WebDavCommandDispatcher($this);
    }

    protected function doBefore(): void
    {
        // no-op
    }

    protected function dispatch(): void
    {
        $this->response = $this->dispatcher->dispatch();
    }

    protected function doAfter(): void
    {
        // no-op
    }
}
