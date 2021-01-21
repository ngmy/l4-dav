<?php

declare(strict_types=1);

namespace Ngmy\L4Dav\Tests\Unit;

use anlutro\cURL\{
    cURL as Curl,
    Response as CurlResponse,
};
use Mockery;
use Ngmy\L4Dav\{
    Request,
    Response,
};
use Ngmy\L4Dav\Tests\TestCase;

class RequestTest extends TestCase
{
    public function testMethod(): void
    {
        $request = new Request(new Curl());

        $retVal = $request->method('POST');

        $this->assertInstanceOf(Request::class, $retVal);
    }

    public function testUrl(): void
    {
        $request = new Request(new Curl());

        $retVal = $request->url('http://localhost/webdav/dir/');

        $this->assertInstanceOf(Request::class, $retVal);
    }

    public function testHeaders(): void
    {
        $request = new Request(new Curl());

        $retVal = $request->headers(['Depth' => '1']);

        $this->assertInstanceOf(Request::class, $retVal);
    }

    public function testOptions(): void
    {
        $request = new Request(new Curl());

        $retVal = $request->options([CURLOPT_NOBODY => true]);

        $this->assertInstanceOf(Request::class, $retVal);
    }

    public function testSend(): void
    {
        $response = Mockery::mock(CurlResponse::class);

        $curl = Mockery::mock(Curl::class);
        $curl->shouldReceive('setRequestClass');
        $curl->shouldReceive('newRequest->setHeaders->setOptions->send')
             ->andReturn($response);

        $request = new Request($curl);

        $response = $request->method('PROPFIND')
            ->url('http://localhost/webdav/')
            ->headers(['Depth' => '1'])
            ->options([CURLOPT_PORT => 80])
            ->send();

        $this->assertInstanceOf(Response::class, $response);
    }
}
