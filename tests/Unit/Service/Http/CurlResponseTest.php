<?php

declare(strict_types=1);

namespace Ngmy\L4Dav\Tests\Service\Http;

use Ngmy\L4Dav\Service\Http\CurlResponse;
use Ngmy\L4Dav\Tests\TestCase;

class CurlResponseTest extends TestCase
{
    /** @var string */
    private $body = <<<EOF
<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 2.0//EN">
<html><head>
<title>'
EOF;
    /** @var array<string, string> */
    private $headers = [
        'HTTP/1.1'       => '201 Created',
        'Date'           => 'Sun, 12 Oct 2014 18:21:23 GMT',
        'Server'         => 'Apache/2.4.9 (Amazon) PHP/5.4.26',
        'Location'       => 'http://localhost/webdav/dir/',
        'Content-Length' => '71',
        'Content-Type'   => 'ext/html; charset=ISO-8859-1',
    ];

    public function testGetBody(): void
    {
        $response = new CurlResponse($this->body, $this->headers);

        $this->assertEquals($this->body, $response->getBody());
    }

    public function testGetStatus(): void
    {
        $response = new CurlResponse($this->body, $this->headers);

        $this->assertEquals(201, $response->getStatus());
    }

    public function testGetMessage(): void
    {
        $response = new CurlResponse($this->body, $this->headers);

        $this->assertEquals($this->headers['HTTP/1.1'], $response->getMessage());
    }
}
