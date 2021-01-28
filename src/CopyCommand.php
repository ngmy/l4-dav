<?php

declare(strict_types=1);

namespace Ngmy\L4Dav;

class CopyCommand extends Command
{
    /**
     * @param WebDavClientOptions $options
     * @param string              $srcUri
     * @param string              $destUri
     * @return void
     */
    public function __construct(WebDavClientOptions $options, string $srcUri, string $destUri)
    {
        parent::__construct($options, 'Copy', $srcUri, new Headers([
            'Destination' => (string) Utils::resolveUri($destUri, $options),
        ]));
    }
}
