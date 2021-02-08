<?php

declare(strict_types=1);

namespace Ngmy\L4Dav;

use Psr\Http\Message\ResponseInterface;
use RuntimeException;

class GetResponse implements ResponseInterface
{
    use ResponseTrait;

    public function __construct(ResponseInterface $response)
    {
        $this->response = $response;
    }

    /**
     * Write a response body to the specified file.
     *
     * @throws RuntimeException
     */
    public function writeToFile(string $path): void
    {
        $fh = \fopen($path, 'x');
        if ($fh === false) {
            throw new RuntimeException('Failed to create file (' . $path . ')');
        }
        $stream = $this->getBody();
        while (!$stream->eof()) {
            \fwrite($fh, $stream->read(2048));
        }
        \fclose($fh);
    }
}
