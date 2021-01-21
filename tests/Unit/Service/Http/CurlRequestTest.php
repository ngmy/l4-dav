<?php

declare(strict_types=1);

namespace Ngmy\L4Dav\Tests\Unit\Service\Http;

use Mockery;
use Ngmy\L4Dav\Library\Curl;
use Ngmy\L4Dav\Service\Http\CurlRequest;
use Ngmy\L4Dav\Service\Http\ResponseInterface;
use Ngmy\L4Dav\Tests\TestCase;

class CurlRequestTest extends TestCase
{
    public function testMethod(): void
    {
        $request = new CurlRequest(new Curl());

        $retVal = $request->method('POST');

        $this->assertInstanceOf(CurlRequest::class, $retVal);
    }

    public function testUrl(): void
    {
        $request = new CurlRequest(new Curl());

        $retVal = $request->url('http://localhost/webdav/dir/');

        $this->assertInstanceOf(CurlRequest::class, $retVal);
    }

    public function testHeaders(): void
    {
        $request = new CurlRequest(new Curl());

        $retVal = $request->headers(['Depth' => '1']);

        $this->assertInstanceOf(CurlRequest::class, $retVal);
    }

    public function testOptions(): void
    {
        $request = new CurlRequest(new Curl());

        $retVal = $request->options([CURLOPT_NOBODY => true]);

        $this->assertInstanceOf(CurlRequest::class, $retVal);
    }

    public function testSend(): void
    {
        $curl = Mockery::mock(Curl::class);
        $request = new CurlRequest($curl);

        $response = Mockery::mock(ResponseInterface::class);
        $curl->shouldReceive('newRequest->setHeaders->setOptions->send')
             ->andReturn($response);

        $response = $request->method('PROPFIND')
            ->url('http://localhost/webdav/')
            ->headers(['Depth' => '1'])
            ->options([CURLOPT_PORT => 80])
            ->send();

        $this->assertInstanceOf(ResponseInterface::class, $response);
    }
}
