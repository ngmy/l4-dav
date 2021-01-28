<?php

declare(strict_types=1);

namespace Ngmy\L4Dav;

use Http\Client\HttpClient;

class HttpClientFactory
{
    /**
     * @return HttpClient
     */
    public static function create(WebDavClientOptions $options): HttpClient
    {
        return new CurlHttpClientWrapper($options);
    }
}
