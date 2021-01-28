<?php

declare(strict_types=1);

namespace Ngmy\L4Dav;

class MakeDirectoryCommand extends Command
{
    /**
     * @param WebDavClientOptions $options
     * @param string              $uri
     * @return void
     */
    public function __construct(WebDavClientOptions $options, string $uri)
    {
        parent::__construct($options, 'MKCOL', $uri);
    }
}
