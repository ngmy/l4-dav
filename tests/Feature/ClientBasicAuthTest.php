<?php

declare(strict_types=1);

namespace Ngmy\L4Dav\Tests\Feature;

class ClientBasicAuthTest extends ClientTest
{
    protected $webdav = '/webdav_basic_auth/';
    protected $username = 'basic';
    protected $password = 'basic';
}
