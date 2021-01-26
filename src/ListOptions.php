<?php

declare(strict_types=1);

namespace Ngmy\L4Dav;

class ListOptions
{
    /** @var Headers */
    private $headers;

    /**
     * @return void
     */
    public function __construct()
    {
        $this->headers = new Headers(['Depth' => '1']);
    }

    /**
     * @return Headers
     */
    public function getHeaders(): Headers
    {
        return $this->headers;
    }
}
