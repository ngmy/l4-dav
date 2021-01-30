<?php

declare(strict_types=1);

namespace Ngmy\L4Dav\Tests\Feature;

class ClientDigestAuthTest extends ClientTest
{
    protected $webDavBasePath = '/webdav_digest_auth/';
    protected $webDavUserName = 'digest';
    protected $webDavPassword = 'digest';
}
