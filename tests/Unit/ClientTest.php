<?php

declare(strict_types=1);

namespace Ngmy\PhpWebDav\Tests\Unit;

use Mockery;
use Ngmy\PhpWebDav\Client;
use Ngmy\PhpWebDav\HttpClient;
use Ngmy\PhpWebDav\Response;
use Ngmy\PhpWebDav\Server;
use Ngmy\PhpWebDav\Tests\TestCase;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;

class ClientTest extends TestCase
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
        $httpClient = Mockery::mock(HttpClient::class);
        $server = Mockery::mock(Server::class);
        $client = $this->createClient($httpClient, $server);

        $server->shouldReceive('url->withPath->value');
        $server->shouldReceive('port');
        $response = Mockery::mock(Response::class);
        $httpClient->shouldReceive('request')->andReturn($response);

        $file = vfsStream::newFile('dummy_file')->at($this->root);
        $result = $client->upload($file->url(), 'dummy_file');

        $this->assertInstanceOf(Response::class, $result);
    }

    public function testDeleteFile(): void
    {
        $httpClient = Mockery::mock(HttpClient::class);
        $server = Mockery::mock(Server::class);
        $client = $this->createClient($httpClient, $server);

        $server->shouldReceive('url->withPath->value');
        $server->shouldReceive('port');
        $response = Mockery::mock(Response::class);
        $httpClient->shouldReceive('request')->andReturn($response);

        $result = $client->delete('dummy_file');

        $this->assertInstanceOf(Response::class, $result);
    }

    public function testGetFile(): void
    {
        $httpClient = Mockery::mock(HttpClient::class);
        $server = Mockery::mock(Server::class);
        $client = $this->createClient($httpClient, $server);

        $server->shouldReceive('url->withPath->value');
        $server->shouldReceive('port');
        $response = Mockery::mock(Response::class);
        $httpClient->shouldReceive('request')->andReturn($response);

        $result = $client->download('dummy_file', $this->root->url() . '/dummy_file');

        $this->assertInstanceOf(Response::class, $result);
    }

    public function testCopyFile(): void
    {
        $httpClient = Mockery::mock(HttpClient::class);
        $server = Mockery::mock(Server::class);
        $client = $this->createClient($httpClient, $server);

        $server->shouldReceive('url->withPath->value');
        $server->shouldReceive('port');
        $response = Mockery::mock(Response::class);
        $httpClient->shouldReceive('request')->andReturn($response);

        $result = $client->copy('dummy_file', 'dummy_file2');

        $this->assertInstanceOf(Response::class, $result);
    }

    public function testMoveFile(): void
    {
        $httpClient = Mockery::mock(HttpClient::class);
        $server = Mockery::mock(Server::class);
        $client = $this->createClient($httpClient, $server);

        $server->shouldReceive('url->withPath->value');
        $server->shouldReceive('port');
        $response = Mockery::mock(Response::class);
        $httpClient->shouldReceive('request')->andReturn($response);

        $result = $client->move('dummy_file', 'dummy_file2');

        $this->assertInstanceOf(Response::class, $result);
    }

    public function testMakeDirectory(): void
    {
        $httpClient = Mockery::mock(HttpClient::class);
        $server = Mockery::mock(Server::class);
        $client = $this->createClient($httpClient, $server);

        $server->shouldReceive('url->withPath->value');
        $server->shouldReceive('port');
        $response = Mockery::mock(Response::class);
        $httpClient->shouldReceive('request')->andReturn($response);

        $result = $client->makeDirectory('dir/');

        $this->assertInstanceOf(Response::class, $result);
    }

    public function testCheckExistenceDirectoryIfExists(): void
    {
        $httpClient = Mockery::mock(HttpClient::class);
        $server = Mockery::mock(Server::class);
        $client = $this->createClient($httpClient, $server);

        $server->shouldReceive('url->withPath->value');
        $server->shouldReceive('port');
        $response = Mockery::mock(Response::class);
        $response->shouldReceive('getStatus')->andReturn(200);
        $httpClient->shouldReceive('request')->andReturn($response);

        $result = $client->exists('dir/');

        $this->assertTrue($result);
    }

    public function testCheckExistenceDirectoryIfNotExists(): void
    {
        $httpClient = Mockery::mock(HttpClient::class);
        $server = Mockery::mock(Server::class);
        $client = $this->createClient($httpClient, $server);

        $server->shouldReceive('url->withPath->value');
        $server->shouldReceive('port');
        $response = Mockery::mock(Response::class);
        $response->shouldReceive('getStatus')->andReturn(404);
        $httpClient->shouldReceive('request')->andReturn($response);

        $result = $client->exists('dir/');

        $this->assertFalse($result);
    }

    public function testListDirectoryContentsIfDirectoryIsFound(): void
    {
        $httpClient = Mockery::mock(HttpClient::class);
        $server = Mockery::mock(Server::class);
        $client = $this->createClient($httpClient, $server);

        $server->shouldReceive('url->withPath->value');
        $server->shouldReceive('port');
        $response = Mockery::mock(Response::class);
        $response->shouldReceive('getStatus')->andReturn(207);
        $response->shouldReceive('getBody')->andReturn(\file_get_contents(__DIR__ . '/../data/mock_ls_response.xml'));
        $httpClient->shouldReceive('request')->andReturn($response);

        $result = $client->list('');

        $this->assertEquals('/webdav/', $result[0]);
        $this->assertEquals('/webdav/file', $result[1]);
        $this->assertEquals('/webdav/dir/', $result[2]);
    }

    public function testListDirectoryContentsIfDirectoryIsNotFound(): void
    {
        $httpClient = Mockery::mock(HttpClient::class);
        $server = Mockery::mock(Server::class);
        $client = $this->createClient($httpClient, $server);

        $server->shouldReceive('url->withPath->value');
        $server->shouldReceive('port');
        $response = Mockery::mock(Response::class);
        $response->shouldReceive('getStatus')->andReturn(404);
        $httpClient->shouldReceive('request')->andReturn($response);

        $result = $client->list('');

        $this->assertEmpty($result);
    }

    private function createClient(HttpClient $httpClient, Server $server): Client
    {
        return new Client($httpClient, $server);
    }
}
