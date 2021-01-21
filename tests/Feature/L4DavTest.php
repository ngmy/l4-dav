<?php

declare(strict_types=1);

namespace Ngmy\L4Dav\Tests\Feature;

use anlutro\cURL\cURL;
use Ngmy\L4Dav\L4Dav;
use Ngmy\L4Dav\Request;
use Ngmy\L4Dav\Tests\TestCase;
use RuntimeException;

class L4DavTest extends TestCase
{
    public function tearDown(): void
    {
        $curl = new cURL();
        $request = new Request($curl);

        $l4Dav = new L4Dav($request, 'http://apache2/');

        foreach ($l4Dav->ls('') as $path) {
            if ($path != '/') {
                $curl = new cURL();
                $request = new Request($curl);

                $l4Dav = new L4Dav($request, 'http://apache2/');
                $l4Dav->delete(ltrim($path, '/'));
            }
        }

        parent::tearDown();
    }

    public function testPutFile(): void
    {
        $curl = new cURL();
        $request = new Request($curl);

        $l4Dav = new L4Dav($request, 'http://apache2/');

        $file = $this->createTmpFile();
        $path = stream_get_meta_data($file)['uri'];
        $response = $l4Dav->put($path, 'file');

        $this->assertEquals('201 Created', $response->getMessage());
        $this->assertEquals(201, $response->getStatus());
    }

    public function testDeleteFile(): void
    {
        $curl = new cURL();
        $request = new Request($curl);

        $l4Dav = new L4Dav($request, 'http://apache2/');

        $file = $this->createTmpFile();
        $path = stream_get_meta_data($file)['uri'];
        $response = $l4Dav->put($path, 'file3');
        $response = $l4Dav->delete('file3');

        $this->assertEquals('204 No Content', $response->getMessage());
        $this->assertEquals(204, $response->getStatus());
    }

    public function testGetFile(): void
    {
        $curl = new cURL();
        $request = new Request($curl);

        $l4Dav = new L4Dav($request, 'http://apache2/');

        $file = $this->createTmpFile();
        $path = stream_get_meta_data($file)['uri'];
        $response = $l4Dav->put($path, 'file');

        $file = $this->createTmpFile();
        $path = stream_get_meta_data($file)['uri'];
        $response = $l4Dav->get('file', $path);

        $this->assertEquals('200 OK', $response->getMessage());
        $this->assertEquals(200, $response->getStatus());
    }

    public function testCopyFile(): void
    {
        $curl = new cURL();
        $request = new Request($curl);

        $l4Dav = new L4Dav($request, 'http://apache2/');

        $file = $this->createTmpFile();
        $path = stream_get_meta_data($file)['uri'];
        $response = $l4Dav->put($path, 'file');

        $response = $l4Dav->copy('file', 'file2');

        $this->assertEquals('201 Created', $response->getMessage());
        $this->assertEquals(201, $response->getStatus());
    }

    public function testMoveFile(): void
    {
        $curl = new cURL();
        $request = new Request($curl);

        $l4Dav = new L4Dav($request, 'http://apache2/');

        $file = $this->createTmpFile();
        $path = stream_get_meta_data($file)['uri'];
        $response = $l4Dav->put($path, 'file');

        $response = $l4Dav->move('file', 'file2');

        $this->assertEquals('201 Created', $response->getMessage());
        $this->assertEquals(201, $response->getStatus());
    }

    public function testMakeDirectory(): void
    {
        $curl = new cURL();
        $request = new Request($curl);

        $l4Dav = new L4Dav($request, 'http://apache2/');

        $response = $l4Dav->mkdir('dir/');

        $this->assertEquals('201 Created', $response->getMessage());
        $this->assertEquals(201, $response->getStatus());
    }

    public function testCheckExistenceDirectoryIfExists(): void
    {
        $curl = new cURL();
        $request = new Request($curl);

        $l4Dav = new L4Dav($request, 'http://apache2/');

        $file = $this->createTmpFile();
        $path = stream_get_meta_data($file)['uri'];
        $response = $l4Dav->put($path, 'file');

        $result = $l4Dav->exists('file');

        $this->assertTrue($result);
    }

    public function testCheckExistenceDirectoryIfNotExists(): void
    {
        $curl = new cURL();
        $request = new Request($curl);

        $l4Dav = new L4Dav($request, 'http://apache2/');

        $result = $l4Dav->exists('file');

        $this->assertFalse($result);
    }

    public function testListDirectoryContentsIfDirectoryIsFound(): void
    {
        $curl = new cURL();
        $request = new Request($curl);

        $l4Dav = new L4Dav($request, 'http://apache2/');

        $file = $this->createTmpFile();
        $path = stream_get_meta_data($file)['uri'];
        $l4Dav->put($path, 'file');
        $l4Dav->mkdir('dir/');
        $l4Dav->put($path, 'dir/file');

        $list = $l4Dav->ls('');

        $this->assertEquals('/', $list[0]);
        $this->assertEquals('/file', $list[1]);
        $this->assertEquals('/dir/', $list[2]);

        $list = $l4Dav->ls('dir/');

        $this->assertEquals('/dir/', $list[0]);
        $this->assertEquals('/dir/file', $list[1]);
    }

    public function testListDirectoryContentsIfDirectoryIsNotFound(): void
    {
        $curl = new cURL();
        $request = new Request($curl);

        $l4Dav = new L4Dav($request, 'http://apache2/');

        $list = $l4Dav->ls('dir/');

        $this->assertEmpty($list);
    }

    /**
     * @return resource
     * @throws RuntimeException
     */
    private function createTmpFile()
    {
        $file = tmpfile();
        if ($file === false) {
            throw new RuntimeException();
        }
        return $file;
    }
}
