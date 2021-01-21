<?php

declare(strict_types=1);

namespace Ngmy\L4Dav;

use anlutro\cURL\cURL;
use Ngmy\L4Dav\Curl\Request as CurlRequest;

class Request implements RequestInterface
{
    /** @var string The HTTP method. */
    protected $method;
    /** @var string The request URL. */
    protected $url;
    /** @var int The port number. */
    protected $port;
    /** @var array<string, string> The HTTP headers. */
    protected $headers = [];
    /** @var cURL The cURL class. */
    protected $curl;
    /** @var array<int, mixed> The cURL options. */
    protected $options = [];

    /**
     * Create a new CurlRequest class object.
     *
     * @param cURL $curl The cURL client library.
     * @return void
     */
    public function __construct(cURL $curl)
    {
        $curl->setRequestClass(CurlRequest::class);
        $this->curl = $curl;
    }

    /**
     * Set the HTTP method.
     *
     * @param string $method The HTTP method.
     * @return self Returns self for chainability.
     */
    public function method($method): self
    {
        $this->method = $method;

        return $this;
    }

    /**
     * Set the request url.
     *
     * @param string $url The request url.
     * @return self Returns self for chainability.
     */
    public function url($url): self
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Set the HTTP headers.
     *
     * @param array<string, string> $headers The HTTP headers.
     * @return self Returns self for chainability.
     */
    public function headers(array $headers): self
    {
        $this->headers = $headers;

        return $this;
    }

    /**
     * Set the cURL options.
     *
     * @param array<int, mixed> $options The cURL options.
     * @return self Returns self for chainability.
     */
    public function options(array $options): self
    {
        $this->options = $options;

        return $this;
    }

    /**
     * Send the request by cURL.
     *
     * @return ResponseInterface Returns a CurlResponse class object.
     */
    public function send(): ResponseInterface
    {
        $response = $this->curl->newRequest($this->method, $this->url)
            ->setHeaders($this->headers)
            ->setOptions($this->options)
            ->send();

        return new Response($response);
    }
}
