<?php

declare(strict_types=1);

namespace Ngmy\L4Dav;

use Illuminate\Support\Facades\Facade;

class L4DavFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return L4Dav::class;
    }
}
