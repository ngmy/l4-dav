<?php

declare(strict_types=1);

namespace Ngmy\L4Dav;

use anlutro\cURL\{
    cURL as Curl,
    Request as CurlRequest,
};

class HttpClient
{
    /** @var Curl */
    private $curl;

    /**
     * @param Curl $curl
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
     * @param string                                                           $method
     * @param Url                                                              $url
     * @param array{curl?: array<int, mixed>, headers?: array<string, string>, auth?: array<string, string>} $options
     * @return Response
     */
    public function request(string $method, Url $url, array $options = []): Response
    {
        $options['curl'][CURLOPT_HTTPAUTH] = CURLAUTH_ANY;

        $curlRequest = $this->curl
            ->newRequest($method, $url->value())
            ->setHeaders($options['headers'] ?? [])
            ->setOptions($options['curl'] ?? []);

        if (isset($options['auth'])) {
            $curlRequest->auth($options['auth']['username'], $options['auth']['password']);
        }

        $curlResponse = $curlRequest->send();

        return new Response($curlResponse);
    }
}
