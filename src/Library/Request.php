<?php

declare(strict_types=1);

namespace Ngmy\L4Dav\Library;

use anlutro\cURL\Request as AnlutroRequest;

class Request extends AnlutroRequest
{
    /** @var array<string, bool> Allowed methods => allows postdata */
    public static $methods = [
        'get'      => false,
        'post'     => true,
        'put'      => true,
        'patch'    => true,
        'delete'   => false,
        'options'  => false,
        'mkcol'    => false,
        'copy'     => false,
        'move'     => false,
        'propfind' => false,
    ];
}
