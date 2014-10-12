<?php namespace Ngmy\L4Dav\Tests\Service\Http;
/**
 * Part of the L4Dav package.
 *
 * Licensed under MIT License.
 *
 * @package    L4Dav
 * @version    0.5.0
 * @author     Ngmy <y.nagamiya@gmail.com>
 * @license    http://opensource.org/licenses/MIT MIT License
 * @copyright  (c) 2014, Ngmy <y.nagamiya@gmail.com>
 * @link       https://github.com/ngmy/l4-dav
 */

use Ngmy\L4Dav\Tests\TestCase;
use Ngmy\L4Dav\Service\Http\CurlRequest;
use Ngmy\L4Dav\Service\Http\CurlResponse;
use Ngmy\L4Dav\Library\cURL;

class CurlRequestTest extends TestCase {

	public function testMethod()
	{
		$request = new CurlRequest(new cURL);

		$retVal = $request->method('POST');

		$this->assertInstanceOf('Ngmy\L4Dav\Service\Http\CurlRequest', $retVal);
	}

	public function testUrl()
	{
		$request = new CurlRequest(new cURL);

		$retVal = $request->url('http://localhost/webdav/dir/');

		$this->assertInstanceOf('Ngmy\L4Dav\Service\Http\CurlRequest', $retVal);
	}

	public function testHeaders()
	{
		$request = new CurlRequest(new cURL);

		$retVal = $request->headers(array('Depth' => 1));

		$this->assertInstanceOf('Ngmy\L4Dav\Service\Http\CurlRequest', $retVal);
	}

	public function testOptions()
	{
		$request = new CurlRequest(new cURL);

		$retVal = $request->options(array(CURLOPT_NOBODY => true));

		$this->assertInstanceOf('Ngmy\L4Dav\Service\Http\CurlRequest', $retVal);
	}

	public function testSend()
	{
		$request = new CurlRequest(new MockcURL);

		$response = $request->method('PROPFIND')
			->url('http://localhost/webdav/')
			->headers(array('Depth' => 1))
			->options(array(CURLOPT_PORT => 80))
			->send();

		$this->assertInstanceOf('Ngmy\L4Dav\Service\Http\CurlResponse', $response);
	}

}

class MockcURL extends cURL {

	protected $requestClass = 'Ngmy\L4Dav\Tests\Service\Http\MockRequest';

}

class MockRequest extends \anlutro\cURL\Request {

	public function send()
	{
		$body    = '';
		$headers = array();

		return new CurlResponse($body, $headers);
	}

}
