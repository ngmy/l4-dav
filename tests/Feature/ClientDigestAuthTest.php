<?php

declare(strict_types=1);

namespace Ngmy\L4Dav\Tests\Feature;

class ClientDigestAuthTest extends ClientTest
{
    protected $webdav = '/webdav_digest_auth/';
    protected $username = 'digest';
    protected $password = 'digest';
}
