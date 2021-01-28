<?php

declare(strict_types=1);

namespace Ngmy\L4Dav;

use GuzzleHttp\Psr7\Uri;
use League\Uri\UriResolver;
use Psr\Http\Message\UriInterface;

class Utils
{
    public static function resolveUri(string $uri, WebDavClientOptions $options): UriInterface
    {
        $uri = \is_null($options->getBaseUri())
            ? new Uri($uri)
            : UriResolver::resolve(new Uri($uri), $options->getBaseUri());
        \assert($uri instanceof UriInterface);
        return $uri;
    }
}
