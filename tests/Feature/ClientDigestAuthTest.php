<?php

declare(strict_types=1);

namespace Ngmy\L4Dav\Tests\Feature;

class ClientDigestAuthTest extends ClientTest
{
    /** @var string */
    protected $webDavBasePath = '/webdav_digest_auth/';
    /** @var string */
    protected $webDavUserName = 'digest';
    /** @var string */
    protected $webDavPassword = 'digest';
}
