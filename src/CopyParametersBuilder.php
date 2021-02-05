<?php

declare(strict_types=1);

namespace Ngmy\L4Dav;

use Psr\Http\Message\UriInterface;
use Http\Discovery\Psr17FactoryDiscovery;

class CopyParametersBuilder
{
    /** @var UriInterface */
    private $destUri;
    /** @var bool */
    private $overwrite;

    /**
     * @param string|UriInterface $destUri
     */
    public function setDestUri($destUri): self
    {
        $this->destUri = Psr17FactoryDiscovery::findUriFactory()->createUri((string) $destUri);
        return $this;
    }

    public function setOverwrite(bool $overwrite): self
    {
        $this->overwrite = $overwrite;
        return $this;
    }

    /**
     * Build WebDAV client options.
     */
    public function build(): CopyParameters
    {
        return new CopyParameters(
            $this->destUri,
            $this->overwrite
        );
    }
}
