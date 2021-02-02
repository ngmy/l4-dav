<?php

declare(strict_types=1);

namespace Ngmy\L4Dav;

use Http\Discovery\Psr17FactoryDiscovery;
use Psr\Http\Message\UriInterface;
use RuntimeException;

class UploadCommand extends Command
{
    /**
     * @param string|UriInterface $destUri
     * @throws RuntimeException
     */
    protected function __construct(WebDavClientOptions $options, string $srcPath, $destUri)
    {
        $fh = \fopen($srcPath, 'r');
        if ($fh === false) {
            throw new RuntimeException('Failed to open file (' . $srcPath . ')');
        }
        $body = Psr17FactoryDiscovery::findStreamFactory()->createStreamFromResource($fh);
        parent::__construct($options, 'PUT', $destUri, new Headers([
            'Content-Length' => (string) \filesize($srcPath),
        ]), $body);
    }
}
