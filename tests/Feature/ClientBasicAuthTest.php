<?php

declare(strict_types=1);

namespace Ngmy\PhpWebDav\Tests\Feature;

class ClientBasicAuthTest extends ClientTest
{
    /** @var string */
    protected $webDavBasePath = '/webdav_basic_auth/';
    /** @var string */
    protected $webDavUserName = 'basic';
    /** @var string */
    protected $webDavPassword = 'basic';
    /** @var string */
    protected $webDavAuthType = 'basic';
}
