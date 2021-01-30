<?php

declare(strict_types=1);

namespace Ngmy\L4Dav\Tests\Feature;

class ClientBasicAuthTest extends ClientTest
{
    protected $webDavBasePath = '/webdav_basic_auth/';
    protected $webDavUserName = 'basic';
    protected $webDavPassword = 'basic';
}
