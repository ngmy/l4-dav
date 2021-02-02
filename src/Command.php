<?php

declare(strict_types=1);

namespace Ngmy\L4Dav;

use InvalidArgumentException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;

abstract class Command
{
    /** @var WebDavClientOptions */
    private $options;
    /** @var string */
    private $method;
    /** @var FullUrl */
    private $uri;
    /** @var Headers */
    private $headers;
    /** @var resource|StreamInterface|string|null */
    private $body;
    /** @var CommandDispatcher */
    private $dispatcher;
    /** @var ResponseInterface */
    protected $response;

    /**
     * @param list<mixed> ...$args
     */
    public static function create(string $command, ...$args): self
    {
        $class = '\\Ngmy\\L4Dav\\' . \ucfirst($command) . 'Command';
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

    public function options(): WebDavClientOptions
    {
        return $this->options;
    }

    public function method(): string
    {
        return $this->method;
    }

    public function uri(): FullUrl
    {
        return $this->uri;
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
     * @param string|UriInterface                  $uri
     * @param Headers                              $headers
     * @param resource|StreamInterface|string|null $body
     */
    protected function __construct(
        WebDavClientOptions $options,
        string $method,
        $uri,
        Headers $headers = null,
        $body = null
    ) {
        $this->options = $options;
        $this->method = $method;
        $this->uri = Url::createFullUrl($uri, $options->baseUrl());
        $this->headers = $headers ?: new Headers([]);
        $this->body = $body;
        $this->dispatcher = new CommandDispatcher($this);
    }

    protected function doBefore(): void
    {
        // no-op
    }

    protected function doAfter(): void
    {
        // no-op
    }

    private function dispatch(): void
    {
        $this->response = $this->dispatcher->dispatch();
    }
}
