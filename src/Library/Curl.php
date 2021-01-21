<?php

declare(strict_types=1);

namespace Ngmy\L4Dav\Library;

use Ngmy\L4Dav\Service\Http\CurlResponse;
use anlutro\cURL\cURL as AnlutroCurl;

class Curl extends AnlutroCurl
{
    /** @var array<string, bool> Allowed methods => allows postdata */
    protected $methods = [
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
    /** @var string The response class to use. */
    protected $responseClass = CurlResponse::class;
}
