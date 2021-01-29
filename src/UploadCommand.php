<?php

declare(strict_types=1);

namespace Ngmy\L4Dav;

use GuzzleHttp\Psr7\Stream;
use RuntimeException;

class UploadCommand extends Command
{
    /**
     * @return void
     */
    public function __construct(WebDavClientOptions $options, string $srcPath, string $destUri)
    {
        $fh = \fopen($srcPath, 'r');
        if ($fh === false) {
            throw new RuntimeException('Failed to open file (' . $srcPath . ')');
        }
        $body = new Stream($fh);
        parent::__construct($options, 'PUT', $destUri, new Headers([
            'Content-Length' => (string) \filesize($srcPath),
        ]), $body);
    }
}
