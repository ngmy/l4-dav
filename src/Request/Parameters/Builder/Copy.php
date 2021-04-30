<?php

declare(strict_types=1);

namespace Ngmy\WebDav\Request\Parameters\Builder;

use Http\Discovery\Psr17FactoryDiscovery;
use InvalidArgumentException;
use Ngmy\WebDav\Request;
use Psr\Http\Message\UriInterface;

use function is_null;

class Copy
{
    /**
     * The destination resource URL.
     *
     * @var UriInterface|null
     */
    private $destinationUrl;
    /**
     * Whether to overwrite the resource if it exists.
     *
     * @var Request\Header\Overwrite|null
     */
    private $overwrite;

    /**
     * Set the destination resource URL.
     *
     * @param string|UriInterface $destinationUrl The destination resource URL
     * @return $this The value of the calling object
     */
    public function setDestinationUrl($destinationUrl): self
    {
        $this->destinationUrl = Psr17FactoryDiscovery::findUriFactory()->createUri((string) $destinationUrl);
        return $this;
    }

    /**
     * Set whether to overwrite the resource if it exists.
     *
     * @param bool $overwrite Whether to overwrite the resource if it exists
     * @return $this The value of the calling object
     */
    public function setOverwrite(bool $overwrite): self
    {
        $this->overwrite = Request\Header\Overwrite::getInstance($overwrite);
        return $this;
    }

    /**
     * Build a new instance of a parameters class for the WebDAV COPY operation.
     *
     * @return Request\Parameters\Copy A new instance of a parameter class for the WebDAV COPY operation
     */
    public function build(): Request\Parameters\Copy
    {
        if (is_null($this->destinationUrl)) {
            throw new InvalidArgumentException();
        }
        return new Request\Parameters\Copy($this->destinationUrl, $this->overwrite);
    }
}
