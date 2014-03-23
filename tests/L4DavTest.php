<?php namespace Ngmy\L4Dav\Tests;
/**
 * Part of the L4Dav package.
 *
 * Licensed under MIT License.
 *
 * @package    L4Dav
 * @version    0.2.0
 * @author     Ngmy <y.nagamiya@gmail.com>
 * @license    http://opensource.org/licenses/MIT MIT License
 * @copyright  (c) 2014, Ngmy <y.nagamiya@gmail.com>
 * @link       https://github.com/ngmy/l4-dav
 */

use \Ngmy\L4Dav\L4Dav;

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
		$responseClass = new \Ngmy\L4Dav\Response('', '');
		$responseClass->statusText = '201 Created';
		$responseClass->statusCode = 201;

		$stub = $this->getMock(
			'\Ngmy\L4Dav\L4Dav',
			array('executeWebRequest'),
			array('http://localhost/webdav/')
		);

		$stub->expects($this->any())
			->method('executeWebRequest')
			->will($this->returnValue($responseClass));

		$response = $stub->put(__DIR__.'/dummy.txt', 'dummy.txt');

		$this->assertEquals('201 Created', $response->getMessage());
		$this->assertEquals(201, $response->getStatus());
	}

	public function testDeleteFile()
	{
		$responseClass = new \Ngmy\L4Dav\Response('', '');
		$responseClass->statusText = '204 No Content';
		$responseClass->statusCode = 204;

		$stub = $this->getMock(
			'\Ngmy\L4Dav\L4Dav',
			array('executeWebRequest'),
			array('http://localhost/webdav/')
		);

		$stub->expects($this->any())
			->method('executeWebRequest')
			->will($this->returnValue($responseClass));

		$response = $stub->delete('dummy.txt');

		$this->assertEquals('204 No Content', $response->getMessage());
		$this->assertEquals(204, $response->getStatus());
	}

	public function testGetFile()
	{
		$responseClass = new \Ngmy\L4Dav\Response('', '');
		$responseClass->statusText = '200 OK';
		$responseClass->statusCode = 200;

		$stub = $this->getMock(
			'\Ngmy\L4Dav\L4Dav',
			array('executeWebRequest'),
			array('http://localhost/webdav/')
		);

		$stub->expects($this->any())
			->method('executeWebRequest')
			->will($this->returnValue($responseClass));

		$response = $stub->get('dummy.txt', __DIR__.'/dummy.txt');

		$this->assertEquals('200 OK', $response->getMessage());
		$this->assertEquals(200, $response->getStatus());
	}

	public function testCopyFile()
	{
		$responseClass = new \Ngmy\L4Dav\Response('', '');
		$responseClass->statusText = '201 Created';
		$responseClass->statusCode = 201;

		$stub = $this->getMock(
			'\Ngmy\L4Dav\L4Dav',
			array('executeWebRequest'),
			array('http://localhost/webdav/')
		);

		$stub->expects($this->any())
			->method('executeWebRequest')
			->will($this->returnValue($responseClass));

		$response = $stub->copy('dummy.txt', 'dummy2.txt');

		$this->assertEquals('201 Created', $response->getMessage());
		$this->assertEquals(201, $response->getStatus());
	}

	public function testMoveFile()
	{
		$responseClass = new \Ngmy\L4Dav\Response('', '');
		$responseClass->statusText = '201 Created';
		$responseClass->statusCode = 201;

		$stub = $this->getMock(
			'\Ngmy\L4Dav\L4Dav',
			array('executeWebRequest'),
			array('http://localhost/webdav/')
		);

		$stub->expects($this->any())
			->method('executeWebRequest')
			->will($this->returnValue($responseClass));

		$response = $stub->move('dummy.txt', 'dummy2.txt');

		$this->assertEquals('201 Created', $response->getMessage());
		$this->assertEquals(201, $response->getStatus());
	}

	public function testMakeDirectory()
	{
		$responseClass = new \Ngmy\L4Dav\Response('', '');
		$responseClass->statusText = '201 Created';
		$responseClass->statusCode = 201;

		$stub = $this->getMock(
			'\Ngmy\L4Dav\L4Dav',
			array('executeWebRequest'),
			array('http://localhost/webdav/')
		);

		$stub->expects($this->any())
			->method('executeWebRequest')
			->will($this->returnValue($responseClass));

		$response = $stub->mkdir('dir/');

		$this->assertEquals('201 Created', $response->getMessage());
		$this->assertEquals(201, $response->getStatus());
	}

	public function testCheckExistenceDirectory()
	{
		// If exists
		$responseClass = new \Ngmy\L4Dav\Response('', '');
		$responseClass->statusText = '201 OK';
		$responseClass->statusCode = 200;

		$stub = $this->getMock(
			'\Ngmy\L4Dav\L4Dav',
			array('executeWebRequest'),
			array('http://localhost/webdav/')
		);

		$stub->expects($this->any())
			->method('executeWebRequest')
			->will($this->returnValue($responseClass));

		$result = $stub->exists('dir/');

		$this->assertTrue($result);

		// If not exists
		$responseClass = new \Ngmy\L4Dav\Response('', '');
		$responseClass->statusText = '404 Not Found';
		$responseClass->statusCode = 404;

		$stub = $this->getMock(
			'\Ngmy\L4Dav\L4Dav',
			array('executeWebRequest'),
			array('http://localhost/webdav/')
		);

		$stub->expects($this->any())
			->method('executeWebRequest')
			->will($this->returnValue($responseClass));

		$result = $stub->exists('dir/');

		$this->assertFalse($result);
	}

	public function testListDirectoryContents()
	{
		$responseClass = new \Ngmy\L4Dav\Response('', '');
		$responseClass->statusText = '207 Multi-Status';
		$responseClass->statusCode = 207;
		$responseClass->body = file_get_contents(__DIR__.'/mock_ls_response.xml');

		$stub = $this->getMock(
			'\Ngmy\L4Dav\L4Dav',
			array('executeWebRequest'),
			array('http://localhost/webdav/')
		);

		$stub->expects($this->any())
			->method('executeWebRequest')
			->will($this->returnValue($responseClass));

		$list = $stub->ls('');

		$this->assertEquals('/webdav/', $list[0]);
		$this->assertEquals('/webdav/file', $list[1]);
		$this->assertEquals('/webdav/dir/', $list[2]);
	}

	/**
	 * @expectedException \InvalidArgumentException
	 */
	public function testInvalidURL()
	{
		$l4Dav = new L4Dav('invalidurl');
	}

}
