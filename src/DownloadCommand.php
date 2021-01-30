<?php

declare(strict_types=1);

namespace Ngmy\L4Dav;

use Psr\Http\Message\UriInterface;
use RuntimeException;

class DownloadCommand extends Command
{
    /** @var string */
    private $destPath;

    /**
     * @param string|UriInterface $srcUri
     */
    public function __construct(WebDavClientOptions $options, $srcUri, string $destPath)
    {
        parent::__construct($options, 'GET', $srcUri);
        $this->destPath = $destPath;
    }

    /**
     * @throws RuntimeException
     */
    protected function postRequest(): void
    {
        $fh = \fopen($this->destPath, 'x');
        if ($fh === false) {
            throw new RuntimeException('Failed to create file (' . $this->destPath . ')');
        }
        $stream = parent::getResponse()->getBody();
        while (!$stream->eof()) {
            \fwrite($fh, $stream->read(2048));
        }
        \fclose($fh);
    }
}
