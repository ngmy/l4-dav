<?php

declare(strict_types=1);

namespace Ngmy\L4Dav;

use Psr\Http\Message\UriInterface;
use RuntimeException;

class GetCommand extends WebDavCommand
{
    /** @var GetParameters */
    protected $parameters;

    /**
     * @param string|UriInterface $url
     */
    protected function __construct($url, GetParameters $parameters, WebDavClientOptions $options)
    {
        parent::__construct('GET', $url, $options);
        $this->parameters = $parameters;
    }

    /**
     * @throws RuntimeException
     */
    protected function doAfter(): void
    {
        if (!\is_null($this->parameters->destPath())) {
            $fh = \fopen($this->parameters->destPath(), 'x');
            if ($fh === false) {
                throw new RuntimeException('Failed to create file (' . $this->parameters->destPath() . ')');
            }
            $stream = $this->response->getBody();
            while (!$stream->eof()) {
                \fwrite($fh, $stream->read(2048));
            }
            \fclose($fh);
        }
    }
}
