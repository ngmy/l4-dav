<?php namespace Ngmy\L4Dav\Tests;
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
use Ngmy\L4Dav\L4Dav;
use Ngmy\L4Dav\Service\Http\RequestInterface;
use Ngmy\L4Dav\Service\Http\ResponseInterface;

class L4DavTest extends TestCase {

	public function setUp()
	{
		parent::setUp();

		touch(__DIR__.'/dummy.txt');
	}

	public function tearDown()
	{
		parent::tearDown();

		unlink(__DIR__.'/dummy.txt');
	}

	public function testPutFile()
	{
		$responseClass = new MockResponse(array(
			'body'       => '',
			'statusCode' => 201,
			'statusText' => '201 Created',
		));

		$requestClass = new MockRequest($responseClass);

		$l4Dav = new L4Dav($requestClass, 'http://localhost/webdav/');

		$response = $l4Dav->put(__DIR__.'/dummy.txt', 'dummy.txt');

		$this->assertEquals('201 Created', $response->getMessage());
		$this->assertEquals(201, $response->getStatus());
	}

	public function testDeleteFile()
	{
		$responseClass = new MockResponse(array(
			'body'       => '',
			'statusCode' => 204,
			'statusText' => '204 No Content',
		));

		$requestClass = new MockRequest($responseClass);

		$l4Dav = new L4Dav($requestClass, 'http://localhost/webdav/');

		$response = $l4Dav->delete('dummy.txt');

		$this->assertEquals('204 No Content', $response->getMessage());
		$this->assertEquals(204, $response->getStatus());
	}

	public function testGetFile()
	{
		$responseClass = new MockResponse(array(
			'body'       => '',
			'statusCode' => 200,
			'statusText' => '200 OK',
		));

		$requestClass = new MockRequest($responseClass);

		$l4Dav = new L4Dav($requestClass, 'http://localhost/webdav/');

		$response = $l4Dav->get('dummy.txt', __DIR__.'/dummy.txt');

		$this->assertEquals('200 OK', $response->getMessage());
		$this->assertEquals(200, $response->getStatus());
	}

	public function testCopyFile()
	{
		$responseClass = new MockResponse(array(
			'body'       => '',
			'statusCode' => 201,
			'statusText' => '201 Created',
		));

		$requestClass = new MockRequest($responseClass);

		$l4Dav = new L4Dav($requestClass, 'http://localhost/webdav/');

		$response = $l4Dav->copy('dummy.txt', 'dummy2.txt');

		$this->assertEquals('201 Created', $response->getMessage());
		$this->assertEquals(201, $response->getStatus());
	}

	public function testMoveFile()
	{
		$responseClass = new MockResponse(array(
			'body'       => '',
			'statusCode' => 201,
			'statusText' => '201 Created',
		));

		$requestClass = new MockRequest($responseClass);

		$l4Dav = new L4Dav($requestClass, 'http://localhost/webdav/');

		$response = $l4Dav->move('dummy.txt', 'dummy2.txt');

		$this->assertEquals('201 Created', $response->getMessage());
		$this->assertEquals(201, $response->getStatus());
	}

	public function testMakeDirectory()
	{
		$responseClass = new MockResponse(array(
			'body'       => '',
			'statusCode' => 201,
			'statusText' => '201 Created',
		));

		$requestClass = new MockRequest($responseClass);

		$l4Dav = new L4Dav($requestClass, 'http://localhost/webdav/');

		$response = $l4Dav->mkdir('dir/');

		$this->assertEquals('201 Created', $response->getMessage());
		$this->assertEquals(201, $response->getStatus());
	}

	public function testCheckExistenceDirectory()
	{
		// If exists
		$responseClass = new MockResponse(array(
			'body'       => '',
			'statusCode' => 200,
			'statusText' => '200 OK',
		));

		$requestClass = new MockRequest($responseClass);

		$l4Dav = new L4Dav($requestClass, 'http://localhost/webdav/');

		$result = $l4Dav->exists('dir/');

		$this->assertTrue($result);

		// If not exists
		$responseClass = new MockResponse(array(
			'body'       => '',
			'statusCode' => 404,
			'statusText' => '404 Not Found',
		));

		$requestClass = new MockRequest($responseClass);

		$l4Dav = new L4Dav($requestClass, 'http://localhost/webdav/');

		$result = $l4Dav->exists('dir/');

		$this->assertFalse($result);
	}

	public function testListDirectoryContents()
	{
		// If the directory is found
		$responseClass = new MockResponse(array(
			'body'       => file_get_contents(__DIR__.'/mock_ls_response.xml'),
			'statusCode' => 207,
			'statusText' => '207 Multi-Status',
		));

		$requestClass = new MockRequest($responseClass);

		$l4Dav = new L4Dav($requestClass, 'http://localhost/webdav/');

		$list = $l4Dav->ls('');

		$this->assertEquals('/webdav/', $list[0]);
		$this->assertEquals('/webdav/file', $list[1]);
		$this->assertEquals('/webdav/dir/', $list[2]);

		// If the directory is not found
		$responseClass = new MockResponse(array(
			'body'       => '',
			'statusCode' => 404,
			'statusText' => '404 Not Found',
		));

		$requestClass = new MockRequest($responseClass);

		$l4Dav = new L4Dav($requestClass, 'http://localhost/webdav/');

		$list = $l4Dav->ls('');

		$this->assertEmpty($list);
	}

	/**
	 * @expectedException \InvalidArgumentException
	 */
	public function testInvalidURL()
	{
		$responseClass = new MockResponse(array(
			'body'       => null,
			'statusCode' => null,
			'statusText' => null,
		));

		$requestClass = new MockRequest($responseClass);

		$l4Dav = new L4Dav($requestClass, 'invalidurl');
	}

}

class MockRequest implements RequestInterface {

	protected $response;

	public function __construct(ResponseInterface $response)
	{
		$this->response = $response;
	}

	public function method($method)
	{
		return $this;
	}

	public function url($url)
	{
		return $this;
	}

	public function headers(array $headers)
	{
		return $this;
	}

	public function options(array $options)
	{
		return $this;
	}

	public function send()
	{
		return $this->response;
	}

}

class MockResponse implements ResponseInterface {

	public function __construct(array $data)
	{
		$this->body       = $data['body'];
		$this->statusCode = $data['statusCode'];
		$this->statusText = $data['statusText'];
	}

	public function getBody()
	{
		return $this->body;
	}

	public function getStatus()
	{
		return (int) $this->statusCode;
	}

	public function getMessage()
	{
		return $this->statusText;
	}

}
