<?php

declare(strict_types=1);

namespace Ngmy\WebDav\Client;

use Ngmy\WebDav\Request;

class Options
{
    /** @var Request\Url\Base|null */
    private $baseUrl;
    /** @var Request\Headers */
    private $defaultRequestHeaders;

    public function __construct(
        ?Request\Url\Base $baseUrl,
        Request\Headers $defaultRequestHeaders
    ) {
        $this->baseUrl = $baseUrl;
        $this->defaultRequestHeaders = $defaultRequestHeaders;
    }

    public function getBaseUrl(): ?Request\Url\Base
    {
        return $this->baseUrl;
    }

    public function getDefaultRequestHeaders(): Request\Headers
    {
        return $this->defaultRequestHeaders;
    }
}
