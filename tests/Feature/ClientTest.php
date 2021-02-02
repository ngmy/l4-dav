<?php

declare(strict_types=1);

namespace Ngmy\L4Dav\Tests\Feature;

use Ngmy\L4Dav\Tests\TestCase;
use Ngmy\L4Dav\WebDavClient;
use Ngmy\L4Dav\WebDavClientOptionsBuilder;
use RuntimeException;

class ClientTest extends TestCase
{
    /** @var string */
    protected $webDavBasePath = '/webdav_no_auth/';

    public function tearDown(): void
    {
        $this->deleteWebDav();

        parent::tearDown();
    }

    public function testPutFile(): void
    {
        $client = $this->createClient();

        $file = $this->createTmpFile();
        $path = \stream_get_meta_data($file)['uri'];
        $response = $client->upload($path, 'file');

        $this->assertEquals('Created', $response->getReasonPhrase());
        $this->assertEquals(201, $response->getStatusCode());
    }

    public function testDeleteFile(): void
    {
        $client = $this->createClient();

        $file = $this->createTmpFile();
        $path = \stream_get_meta_data($file)['uri'];
        $response = $client->upload($path, 'file');
        $response = $client->delete('file');

        $this->assertEquals('No Content', $response->getReasonPhrase());
        $this->assertEquals(204, $response->getStatusCode());
    }

    public function testGetFile(): void
    {
        $client = $this->createClient();

        $file = $this->createTmpFile();
        $path = \stream_get_meta_data($file)['uri'];
        $response = $client->upload($path, 'file');

        $file = $this->createTmpFile();
        $path = \stream_get_meta_data($file)['uri'];
        \unlink($path);
        $response = $client->download('file', $path);

        $this->assertEquals('OK', $response->getReasonPhrase());
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testCopyFile(): void
    {
        $client = $this->createClient();

        $file = $this->createTmpFile();
        $path = \stream_get_meta_data($file)['uri'];
        $response = $client->upload($path, 'file');

        $response = $client->copy('file', 'file2');

        $this->assertEquals('Created', $response->getReasonPhrase());
        $this->assertEquals(201, $response->getStatusCode());
    }

    public function testMoveFile(): void
    {
        $client = $this->createClient();

        $file = $this->createTmpFile();
        $path = \stream_get_meta_data($file)['uri'];
        $response = $client->upload($path, 'file');

        $response = $client->move('file', 'file2');

        $this->assertEquals('Created', $response->getReasonPhrase());
        $this->assertEquals(201, $response->getStatusCode());
    }

    public function testMakeDirectory(): void
    {
        $client = $this->createClient();

        $response = $client->makeDirectory('dir/');

        $this->assertEquals('Created', $response->getReasonPhrase());
        $this->assertEquals(201, $response->getStatusCode());
    }

    public function testCheckExistenceDirectoryIfExists(): void
    {
        $client = $this->createClient();

        $file = $this->createTmpFile();
        $path = \stream_get_meta_data($file)['uri'];
        $response = $client->upload($path, 'file');

        $response = $client->exists('file');

        $this->assertEquals('OK', $response->getReasonPhrase());
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($response->exists());
    }

    public function testCheckExistenceDirectoryIfNotExists(): void
    {
        $client = $this->createClient();

        $response = $client->exists('file');

        $this->assertEquals('Not Found', $response->getReasonPhrase());
        $this->assertEquals(404, $response->getStatusCode());
        $this->assertFalse($response->exists());
    }

    public function testListDirectoryContentsIfDirectoryIsFound(): void
    {
        $client = $this->createClient();

        $file = $this->createTmpFile();
        $path = \stream_get_meta_data($file)['uri'];
        $client->upload($path, 'file');
        $client->makeDirectory('dir/');
        $client->upload($path, 'dir/file');

        $response = $client->list('');

        $this->assertEquals('Multi-Status', $response->getReasonPhrase());
        $this->assertEquals(207, $response->getStatusCode());
        $this->assertEquals($this->webDavBasePath, $response->getList()[0]);
        $this->assertEquals($this->webDavBasePath . 'file', $response->getList()[1]);
        $this->assertEquals($this->webDavBasePath . 'dir/', $response->getList()[2]);

        $response = $client->list('dir/');

        $this->assertEquals('Multi-Status', $response->getReasonPhrase());
        $this->assertEquals(207, $response->getStatusCode());
        $this->assertEquals($this->webDavBasePath . 'dir/', $response->getList()[0]);
        $this->assertEquals($this->webDavBasePath . 'dir/file', $response->getList()[1]);
    }

    public function testListDirectoryContentsIfDirectoryIsNotFound(): void
    {
        $client = $this->createClient();

        $response = $client->list('dir/');

        $this->assertEquals('Not Found', $response->getReasonPhrase());
        $this->assertEquals(404, $response->getStatusCode());
        $this->assertEmpty($response->getList());
    }

    protected function createClient(): WebDavClient
    {
        $optionsBuilder = (new WebDavClientOptionsBuilder())
            ->baseUrl('http://apache2' . $this->webDavBasePath);
        if (isset($this->webDavUserName)) {
            $optionsBuilder->userName($this->webDavUserName);
        }
        if (isset($this->webDavPassword)) {
            $optionsBuilder->password($this->webDavPassword);
        }
        $options = $optionsBuilder->build();
        return new WebDavClient($options);
    }

    /**
     * @throws RuntimeException
     * @return resource
     */
    protected function createTmpFile()
    {
        $file = \tmpfile();
        if ($file === false) {
            throw new RuntimeException('Failed to create temporary file');
        }
        \fwrite($file, 'This is test file.');
        return $file;
    }

    protected function deleteWebDav(string $directoryPath = ''): void
    {
        $client = $this->createClient();
        foreach ($client->list($directoryPath)->getList() as $path) {
            if ($path == $this->webDavBasePath . $directoryPath) {
                continue;
            }
            if (\preg_match("|{$this->webDavBasePath}(.*\/)$|", $path, $matches)) {
                $this->deleteWebDav($matches[1]);
            }
            $client = $this->createClient();
            \assert(!\is_null(\preg_replace("|{$this->webDavBasePath}(.*)|", '\1', $path)));
            $client->delete(\preg_replace("|{$this->webDavBasePath}(.*)|", '\1', $path));
        }
    }
}
