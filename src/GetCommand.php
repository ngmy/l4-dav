<?php

declare(strict_types=1);

namespace Ngmy\L4Dav;

use Psr\Http\Message\UriInterface;
use RuntimeException;

class GetCommand extends Command
{
    /** @var string */
    private $destPath;

    /**
     * @param string|UriInterface $srcUri
     */
    protected function __construct(WebDavClientOptions $options, $srcUri, string $destPath)
    {
        parent::__construct($options, 'GET', $srcUri);
        $this->destPath = $destPath;
    }

    /**
     * @throws RuntimeException
     */
    protected function doAfter(): void
    {
        $fh = \fopen($this->destPath, 'x');
        if ($fh === false) {
            throw new RuntimeException('Failed to create file (' . $this->destPath . ')');
        }
        $stream = $this->response->getBody();
        while (!$stream->eof()) {
            \fwrite($fh, $stream->read(2048));
        }
        \fclose($fh);
    }
}
