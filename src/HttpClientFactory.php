<?php

declare(strict_types=1);

namespace Ngmy\L4Dav;

use Http\Client\HttpClient;

class HttpClientFactory
{
    public static function create(WebDavClientOptions $options): HttpClient
    {
        return new CurlHttpClientWrapper($options);
    }
}
