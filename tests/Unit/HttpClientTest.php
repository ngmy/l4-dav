<?php

declare(strict_types=1);

namespace Ngmy\L4Dav\Tests\Unit;

use anlutro\cURL\cURL as Curl;
use anlutro\cURL\Response as CurlResponse;
use Mockery;
use Ngmy\L4Dav\HttpClient;
use Ngmy\L4Dav\Response;
use Ngmy\L4Dav\Tests\TestCase;
use Ngmy\L4Dav\Url;

class HttpClientTest extends TestCase
{
    public function testHttpClient(): void
    {
        $response = Mockery::mock(CurlResponse::class);
        $curl = Mockery::mock(Curl::class);
        $curl->shouldReceive('setHttpClientClass');
        $curl->shouldReceive('newRequest->setHeaders->setOptions->send')
             ->andReturn($response);

        $httpClient = new HttpClient($curl);
        $url = Mockery::mock(Url::class);
        $url->shouldReceive('value');

        $result = $httpClient->request('PROPFIND', $url, [
            'headers' => ['Depth' => '1'],
            'curl'    => [\CURLOPT_PORT => 80],
        ]);

        $this->assertInstanceOf(Response::class, $result);
    }
}
