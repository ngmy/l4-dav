<?php

declare(strict_types=1);

namespace Ngmy\L4Dav\Tests\Feature;

use Ngmy\L4Dav\Tests\TestCase;
use Ngmy\L4Dav\WebDavClient;
use Ngmy\L4Dav\WebDavClientOptionsBuilder;
use Psr\Http\Message\UriInterface;
use RuntimeException;
use SimpleXMLElement;

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

    /**
     * @return list<list<mixed>>
     */
    public function deleteProvider(): array
    {
        return [
            [
                function () {
                    $file = $this->createTmpFile();
                    $path = \stream_get_meta_data($file)['uri'];
                    $client = $this->createClient();
                    $client->upload($path, 'file');
                },
                'file',
                [
                    'reason_phrase' => 'No Content',
                    'status_code' => 204,
                ],
            ],
            [
                function () {
                    $client = $this->createClient();
                    $client->makeDirectory('dir/');
                },
                'dir/',
                [
                    'reason_phrase' => 'No Content',
                    'status_code' => 204,
                ],
            ],
            [
                function () {
                    $file = $this->createTmpFile();
                    $path = \stream_get_meta_data($file)['uri'];
                    $client = $this->createClient();
                    $client->makeDirectory('dir/');
                    $client->upload($path, 'dir/file');
                },
                'dir/',
                [
                    'reason_phrase' => 'No Content',
                    'status_code' => 204,
                ],
            ],
        ];
    }

    /**
     * @param array<string, string> $expected
     * @dataProvider deleteProvider
     */
    public function testDeleteFile(callable $before, string $uri, array $expected): void
    {
        $before();

        $client = $this->createClient();
        $response = $client->delete($uri);

        $this->assertEquals($expected['reason_phrase'], $response->getReasonPhrase());
        $this->assertEquals($expected['status_code'], $response->getStatusCode());
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

    /**
     * @return list<list<mixed>>
     */
    public function copyProvider(): array
    {
        return [
            [
                function () {
                    $file = $this->createTmpFile();
                    $path = \stream_get_meta_data($file)['uri'];
                    $client = $this->createClient();
                    $client->upload($path, 'file1');
                },
                'file1',
                'file2',
                false,
                [
                    'reason_phrase' => 'Created',
                    'status_code' => 201,
                ],
            ],
            [
                function () {
                    $file = $this->createTmpFile();
                    $path = \stream_get_meta_data($file)['uri'];
                    $client = $this->createClient();
                    $client->upload($path, 'file1');
                    $client->upload($path, 'file2');
                },
                'file1',
                'file2',
                false,
                [
                    'reason_phrase' => 'Precondition Failed',
                    'status_code' => 412,
                ],
            ],
            [
                function () {
                    $file = $this->createTmpFile();
                    $path = \stream_get_meta_data($file)['uri'];
                    $client = $this->createClient();
                    $client->upload($path, 'file1');
                    $client->upload($path, 'file2');
                },
                'file1',
                'file2',
                true,
                [
                    'reason_phrase' => 'No Content',
                    'status_code' => 204,
                ],
            ],
            [
                function () {
                    $file = $this->createTmpFile();
                    $path = \stream_get_meta_data($file)['uri'];
                    $client = $this->createClient();
                    $client->makeDirectory('dir1/');
                    $client->upload($path, 'dir1/file1_1');
                    $client->upload($path, 'dir1/file1_2');
                    $client->makeDirectory('dir1/dir1_2/');
                    $client->upload($path, 'dir1/dir1_2/file1_2_1');
                    $client->upload($path, 'dir1/dir1_2/file1_2_2');
                },
                'dir1/',
                'dir2/',
                false,
                [
                    'reason_phrase' => 'Created',
                    'status_code' => 201,
                ],
            ],
            [
                function () {
                    $file = $this->createTmpFile();
                    $path = \stream_get_meta_data($file)['uri'];
                    $client = $this->createClient();
                    $client->makeDirectory('dir1/');
                    $client->upload($path, 'dir1/file1_1');
                    $client->upload($path, 'dir1/file1_2');
                    $client->makeDirectory('dir1/dir1_2/');
                    $client->upload($path, 'dir1/dir1_2/file1_2_1');
                    $client->upload($path, 'dir1/dir1_2/file1_2_2');
                    $client->makeDirectory('dir2/');
                    $client->upload($path, 'dir2/file2_1');
                    $client->upload($path, 'dir2/file2_2');
                    $client->makeDirectory('dir2/dir2_2/');
                    $client->upload($path, 'dir2/dir2_2/file2_2_1');
                    $client->upload($path, 'dir2/dir2_2/file2_2_2');
                },
                'dir1/',
                'dir2/',
                false,
                [
                    'reason_phrase' => 'Precondition Failed',
                    'status_code' => 412,
                ],
            ],
            [
                function () {
                    $file = $this->createTmpFile();
                    $path = \stream_get_meta_data($file)['uri'];
                    $client = $this->createClient();
                    $client->makeDirectory('dir1/');
                    $client->upload($path, 'dir1/file1_1');
                    $client->upload($path, 'dir1/file1_2');
                    $client->makeDirectory('dir1/dir1_2/');
                    $client->upload($path, 'dir1/dir1_2/file1_2_1');
                    $client->upload($path, 'dir1/dir1_2/file1_2_2');
                    $client->makeDirectory('dir2/');
                    $client->upload($path, 'dir2/file2_1');
                    $client->upload($path, 'dir2/file2_2');
                    $client->makeDirectory('dir2/dir2_2/');
                    $client->upload($path, 'dir2/dir2_2/file2_2_1');
                    $client->upload($path, 'dir2/dir2_2/file2_2_2');
                },
                'dir1/',
                'dir2/',
                true,
                [
                    'reason_phrase' => 'No Content',
                    'status_code' => 204,
                ],
            ],
        ];
    }

    /**
     * @param string|UriInterface $srcUri
     * @param string|UriInterface $destUri
     * @param array<string, string> $expected
     * @dataProvider copyProvider
     */
    public function testCopy(callable $before, $srcUri, $destUri, bool $overwrite, array $expected): void
    {
        $before();

        $client = $this->createClient();
        $response = $client->copy($srcUri, $destUri, $overwrite);

        $this->assertEquals($expected['reason_phrase'], $response->getReasonPhrase());
        $this->assertEquals($expected['status_code'], $response->getStatusCode());
    }

    /**
     * @return list<list<mixed>>
     */
    public function moveProvider(): array
    {
        return [
            [
                function () {
                    $file = $this->createTmpFile();
                    $path = \stream_get_meta_data($file)['uri'];
                    $client = $this->createClient();
                    $client->upload($path, 'file1');
                },
                'file1',
                'file2',
                [
                    'reason_phrase' => 'Created',
                    'status_code' => 201,
                ],
            ],
            [
                function () {
                    $file = $this->createTmpFile();
                    $path = \stream_get_meta_data($file)['uri'];
                    $client = $this->createClient();
                    $client->upload($path, 'file1');
                    $client->upload($path, 'file2');
                },
                'file1',
                'file2',
                [
                    'reason_phrase' => 'No Content',
                    'status_code' => 204,
                ],
            ],
            [
                function () {
                    $file = $this->createTmpFile();
                    $path = \stream_get_meta_data($file)['uri'];
                    $client = $this->createClient();
                    $client->makeDirectory('dir1/');
                    $client->upload($path, 'dir1/file1_1');
                    $client->upload($path, 'dir1/file1_2');
                    $client->makeDirectory('dir1/dir1_2/');
                    $client->upload($path, 'dir1/dir1_2/file1_2_1');
                    $client->upload($path, 'dir1/dir1_2/file1_2_2');
                },
                'dir1/',
                'dir2/',
                [
                    'reason_phrase' => 'Created',
                    'status_code' => 201,
                ],
            ],
            [
                function () {
                    $file = $this->createTmpFile();
                    $path = \stream_get_meta_data($file)['uri'];
                    $client = $this->createClient();
                    $client->makeDirectory('dir1/');
                    $client->upload($path, 'dir1/file1_1');
                    $client->upload($path, 'dir1/file1_2');
                    $client->makeDirectory('dir1/dir1_2/');
                    $client->upload($path, 'dir1/dir1_2/file1_2_1');
                    $client->upload($path, 'dir1/dir1_2/file1_2_2');
                    $client->makeDirectory('dir2/');
                    $client->upload($path, 'dir2/file2_1');
                    $client->upload($path, 'dir2/file2_2');
                    $client->makeDirectory('dir2/dir2_2/');
                    $client->upload($path, 'dir2/dir2_2/file2_2_1');
                    $client->upload($path, 'dir2/dir2_2/file2_2_2');
                },
                'dir1/',
                'dir2/',
                [
                    'reason_phrase' => 'No Content',
                    'status_code' => 204,
                ],
            ],
        ];
    }

    /**
     * @param string|UriInterface $srcUri
     * @param string|UriInterface $destUri
     * @param array<string, string> $expected
     * @dataProvider moveProvider
     */
    public function testMove(callable $before, $srcUri, $destUri, array $expected): void
    {
        $before();

        $client = $this->createClient();
        $response = $client->move($srcUri, $destUri);

        $this->assertEquals($expected['reason_phrase'], $response->getReasonPhrase());
        $this->assertEquals($expected['status_code'], $response->getStatusCode());
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
        $client->makeDirectory('dir/dir2/');
        $client->upload($path, 'dir/dir2/file');

        $response = $client->list('');

        $this->assertEquals('Multi-Status', $response->getReasonPhrase());
        $this->assertEquals(207, $response->getStatusCode());
        $this->assertEquals($this->webDavBasePath, $response->getResponse()->response[0]->href);
        $this->assertEquals($this->webDavBasePath . 'file', $response->getResponse()->response[1]->href);
        $this->assertEquals($this->webDavBasePath . 'dir/', $response->getResponse()->response[2]->href);

        $response = $client->list('dir/');

        $this->assertEquals('Multi-Status', $response->getReasonPhrase());
        $this->assertEquals(207, $response->getStatusCode());
        $this->assertEquals($this->webDavBasePath . 'dir/', $response->getResponse()->response[0]->href);
        $this->assertEquals($this->webDavBasePath . 'dir/file', $response->getResponse()->response[1]->href);
    }

    public function testListDirectoryContentsIfDirectoryIsNotFound(): void
    {
        $client = $this->createClient();

        $response = $client->list('dir/');

        $this->assertEquals('Not Found', $response->getReasonPhrase());
        $this->assertEquals(404, $response->getStatusCode());
        $this->assertEquals(new SimpleXMLElement('<?xml version="1.0" encoding="utf-8"?><root></root>'), $response->getResponse());
    }

    public function testSetProperties(): void
    {
        $file = $this->createTmpFile();
        $path = \stream_get_meta_data($file)['uri'];
        $client = $this->createClient();
        $client->upload($path, 'file');

        $client = $this->createClient();

        $listResponseBefore = $client->list('file');
        $propertyBefore = $listResponseBefore->getResponse();

        $this->assertEquals('F', $propertyBefore->response->propstat->prop->children('http://apache.org/dav/props/')->executable);

        $propertyBefore->response->propstat->prop->children('http://apache.org/dav/props/')->executable = 'T';
        $setPropertiesResponse = $client->setProperties('file', $propertyBefore->response->propstat->prop->children('http://apache.org/dav/props/')->executable);

        $this->assertEquals('Multi-Status', $setPropertiesResponse->getReasonPhrase());
        $this->assertEquals(207, $setPropertiesResponse->getStatusCode());

        $listResponseAfter = $client->list('file');
        $propertyAfter = $listResponseAfter->getResponse();

        $this->assertEquals('T', $propertyAfter->response->propstat->prop->children('http://apache.org/dav/props/')->executable);
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
        foreach ($client->list($directoryPath, 1)->getAllFilesAndDirectories() as $path) {
            if ((string) $path == $this->webDavBasePath . $directoryPath) {
                continue;
            }
            if (\preg_match("|{$this->webDavBasePath}(.*\/)$|", (string) $path, $matches)) {
                $this->deleteWebDav($matches[1]);
            }
            $client = $this->createClient();
            \assert(!\is_null(\preg_replace("|{$this->webDavBasePath}(.*)|", '\1', (string) $path)));
            $client->delete(\preg_replace("|{$this->webDavBasePath}(.*)|", '\1', (string) $path));
        }
    }
}
