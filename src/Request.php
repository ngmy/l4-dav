<?php

declare(strict_types=1);

namespace Ngmy\L4Dav;

use anlutro\cURL\{
    cURL as Curl,
    Request as CurlRequest,
};

class Request
{
    /** @var string The HTTP method. */
    private $method;
    /** @var string The request URL. */
    private $url;
    /** @var array<string, string> The HTTP headers. */
    private $headers = [];
    /** @var Curl The cURL class. */
    private $curl;
    /** @var array<int, mixed> The cURL options. */
    private $options = [];

    /**
     * Create a new CurlRequest class object.
     *
     * @param Curl $curl The cURL client library.
     * @return void
     */
    public function __construct(Curl $curl)
    {
        CurlRequest::$methods = array_merge(CurlRequest::$methods, [
            'copy'     => false,
            'mkcol'    => false,
            'move'     => false,
            'propfind' => false,
        ]);
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
     * @return Response Returns a CurlResponse class object.
     */
    public function send(): Response
    {
        $response = $this->curl->newRequest($this->method, $this->url)
            ->setHeaders($this->headers)
            ->setOptions($this->options)
            ->send();

        return new Response($response);
    }
}
