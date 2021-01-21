<?php

declare(strict_types=1);

namespace Ngmy\L4Dav\Tests\Unit;

use InvalidArgumentException;
use Mockery;
use Ngmy\L4Dav\{
    L4Dav,
    Request,
    Response,
};
use Ngmy\L4Dav\Tests\TestCase;
use org\bovigo\vfs\{
    vfsStream,
    vfsStreamDirectory,
};

class L4DavTest extends TestCase
{
    /** @var vfsStreamDirectory */
    private $root;

    public function setUp(): void
    {
        parent::setUp();

        $this->root = vfsStream::setup();
    }

    public function testPutFile(): void
    {
        $request = Mockery::mock(Request::class);
        $response = Mockery::mock(Response::class);

        $l4Dav = new L4Dav($request, 'http://localhost/webdav/');

        $request->shouldReceive('method->url->options->send')
            ->andReturn($response);

        $file = vfsStream::newFile('dummy_file')->at($this->root);
        $response = $l4Dav->put($file->url(), 'dummy_file');

        $this->assertInstanceOf(Response::class, $response);
    }

    public function testDeleteFile(): void
    {
        $request = Mockery::mock(Request::class);
        $response = Mockery::mock(Response::class);

        $l4Dav = new L4Dav($request, 'http://localhost/webdav/');

        $request->shouldReceive('method->url->options->send')
            ->andReturn($response);

        $response = $l4Dav->delete('dummy_file');

        $this->assertInstanceOf(Response::class, $response);
    }

    public function testGetFile(): void
    {
        $request = Mockery::mock(Request::class);
        $response = Mockery::mock(Response::class);

        $l4Dav = new L4Dav($request, 'http://localhost/webdav/');

        $request->shouldReceive('method->url->options->send')
            ->andReturn($response);

        $response = $l4Dav->get('dummy_file', $this->root->url() . '/dummy_file');

        $this->assertInstanceOf(Response::class, $response);
    }

    public function testCopyFile(): void
    {
        $request = Mockery::mock(Request::class);
        $response = Mockery::mock(Response::class);

        $l4Dav = new L4Dav($request, 'http://localhost/webdav/');

        $request->shouldReceive('method->url->headers->options->send')
            ->andReturn($response);

        $response = $l4Dav->copy('dummy_file', 'dummy_file2');

        $this->assertInstanceOf(Response::class, $response);
    }

    public function testMoveFile(): void
    {
        $request = Mockery::mock(Request::class);
        $response = Mockery::mock(Response::class);

        $l4Dav = new L4Dav($request, 'http://localhost/webdav/');

        $request->shouldReceive('method->url->headers->options->send')
            ->andReturn($response);

        $response = $l4Dav->move('dummy_file', 'dummy_file2');

        $this->assertInstanceOf(Response::class, $response);
    }

    public function testMakeDirectory(): void
    {
        $request = Mockery::mock(Request::class);
        $response = Mockery::mock(Response::class);

        $l4Dav = new L4Dav($request, 'http://localhost/webdav/');

        $request->shouldReceive('method->url->options->send')
            ->andReturn($response);

        $response = $l4Dav->mkdir('dir/');

        $this->assertInstanceOf(Response::class, $response);
    }

    public function testCheckExistenceDirectoryIfExists(): void
    {
        $request = Mockery::mock(Request::class);
        $response = Mockery::mock(Response::class);

        $l4Dav = new L4Dav($request, 'http://localhost/webdav/');

        $request->shouldReceive('method->url->options->send')
            ->andReturn($response);

        $response->shouldReceive('getStatus')->andReturn(200);

        $result = $l4Dav->exists('dir/');

        $this->assertTrue($result);
    }

    public function testCheckExistenceDirectoryIfNotExists(): void
    {
        $request = Mockery::mock(Request::class);
        $response = Mockery::mock(Response::class);

        $l4Dav = new L4Dav($request, 'http://localhost/webdav/');

        $request->shouldReceive('method->url->options->send')
            ->andReturn($response);

        $response->shouldReceive('getStatus')->andReturn(404);

        $result = $l4Dav->exists('dir/');

        $this->assertFalse($result);
    }

    public function testListDirectoryContentsIfDirectoryIsFound(): void
    {
        $request = Mockery::mock(Request::class);
        $response = Mockery::mock(Response::class);

        $l4Dav = new L4Dav($request, 'http://localhost/webdav/');

        $request->shouldReceive('method->url->headers->options->send')
            ->andReturn($response);

        $response->shouldReceive('getStatus')->andReturn(207);
        $response->shouldReceive('getBody')->andReturn(file_get_contents(__DIR__ . '/../data/mock_ls_response.xml'));

        $list = $l4Dav->ls('');

        $this->assertEquals('/webdav/', $list[0]);
        $this->assertEquals('/webdav/file', $list[1]);
        $this->assertEquals('/webdav/dir/', $list[2]);
    }

    public function testListDirectoryContentsIfDirectoryIsNotFound(): void
    {
        $request = Mockery::mock(Request::class);
        $response = Mockery::mock(Response::class);

        $l4Dav = new L4Dav($request, 'http://localhost/webdav/');

        $request->shouldReceive('method->url->headers->options->send')
            ->andReturn($response);

        $response->shouldReceive('getStatus')->andReturn(404);

        $list = $l4Dav->ls('');

        $this->assertEmpty($list);
    }

    public function testInvalidURL(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $request = Mockery::mock(Request::class);

        new L4Dav($request, 'invalidurl');
    }
}
