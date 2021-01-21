<?php

declare(strict_types=1);

namespace Ngmy\L4Dav\Library;

use Ngmy\L4Dav\Service\Http\CurlResponse;
use anlutro\cURL\cURL as AnlutroCurl;

class Curl extends AnlutroCurl
{
    /** @var string The request class to use. */
    protected $requestClass = Request::class;
    /** @var string The response class to use. */
    protected $responseClass = CurlResponse::class;
}
