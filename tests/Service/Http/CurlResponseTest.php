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
use Ngmy\L4Dav\Service\Http\CurlResponse;

class CurlResponseTest extends TestCase {

	protected $body = <<<EOF
<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 2.0//EN">
<html><head>
<title>'
EOF;

	protected $headers = array(
		'HTTP/1.1'       => '201 Created',
		'Date'           => 'Sun, 12 Oct 2014 18:21:23 GMT',
		'Server'         => 'Apache/2.4.9 (Amazon) PHP/5.4.26',
		'Location'       => 'http://localhost/webdav/dir/',
		'Content-Length' => 71,
		'Content-Type'   => 'ext/html; charset=ISO-8859-1',
	);

	public function testGetBody()
	{
		$response = new CurlResponse($this->body, $this->headers);

		$this->assertEquals($this->body, $response->getBody());
	}

	public function testGetStatus()
	{
		$response = new CurlResponse($this->body, $this->headers);

		$this->assertEquals(201, $response->getStatus());
	}

	public function testGetMessage()
	{
		$response = new CurlResponse($this->body, $this->headers);

		$this->assertEquals($this->headers['HTTP/1.1'], $response->getMessage());
	}

}
