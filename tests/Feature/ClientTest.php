<?php

declare(strict_types=1);

namespace Ngmy\L4Dav\Tests\Feature;

use DOMDocument;
use Ngmy\L4Dav\CopyParametersBuilder;
use Ngmy\L4Dav\GetParametersBuilder;
use Ngmy\L4Dav\MoveParametersBuilder;
use Ngmy\L4Dav\PropfindParametersBuilder;
use Ngmy\L4Dav\ProppatchParametersBuilder;
use Ngmy\L4Dav\PutParametersBuilder;
use Ngmy\L4Dav\Tests\TestCase;
use Ngmy\L4Dav\WebDavClient;
use Ngmy\L4Dav\WebDavClientOptionsBuilder;
use Psr\Http\Message\UriInterface;
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

        $parameters = (new PutParametersBuilder())
            ->setSrcPath($path)
            ->build();

        $response = $client->put('file', $parameters);

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
                    $parameters = (new PutParametersBuilder())
                        ->setSrcPath($path)
                        ->build();
                    $client = $this->createClient();
                    $client->put('file', $parameters);
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
                    $client->mkcol('dir/');
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
                    $parameters = (new PutParametersBuilder())
                        ->setSrcPath($path)
                        ->build();
                    $client = $this->createClient();
                    $client->mkcol('dir/');
                    $client->put('dir/file', $parameters);
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

        $parameters = (new PutParametersBuilder())
            ->setSrcPath($path)
            ->build();

        $response = $client->put('file', $parameters);

        $file = $this->createTmpFile();
        $path = \stream_get_meta_data($file)['uri'];
        \unlink($path);

        $parameters = (new GetParametersBuilder())
            ->setDestPath($path)
            ->build();

        $response = $client->get('file');

        $this->assertEquals('OK', $response->getReasonPhrase());
        $this->assertEquals(200, $response->getStatusCode());

        \file_put_contents($path, $response->getBody());

        $this->assertFileExists($path);

        $file = $this->createTmpFile();
        $path = \stream_get_meta_data($file)['uri'];
        \unlink($path);

        $response->getBody()->rewind();

        $fh = \fopen($path, 'x');
        if ($fh === false) {
            throw new RuntimeException('Failed to create file (' . $path . ')');
        }
        $stream = $response->getBody();
        while (!$stream->eof()) {
            \fwrite($fh, $stream->read(2048));
        }
        \fclose($fh);

        $this->assertFileExists($path);
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
                    $parameters = (new PutParametersBuilder())
                        ->setSrcPath($path)
                        ->build();
                    $client = $this->createClient();
                    $client->put('file1', $parameters);
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
                    $parameters = (new PutParametersBuilder())
                        ->setSrcPath($path)
                        ->build();
                    $client = $this->createClient();
                    $client->put('file1', $parameters);
                    $client->put('file2', $parameters);
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
                    $parameters = (new PutParametersBuilder())
                        ->setSrcPath($path)
                        ->build();
                    $client = $this->createClient();
                    $client->put('file1', $parameters);
                    $client->put('file2', $parameters);
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
                    $parameters = (new PutParametersBuilder())
                        ->setSrcPath($path)
                        ->build();
                    $client = $this->createClient();
                    $client->mkcol('dir1/');
                    $client->put('dir1/file1_1', $parameters);
                    $client->put('dir1/file1_2', $parameters);
                    $client->mkcol('dir1/dir1_2/');
                    $client->put('dir1/dir1_2/file1_2_1', $parameters);
                    $client->put('dir1/dir1_2/file1_2_2', $parameters);
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
                    $parameters = (new PutParametersBuilder())
                        ->setSrcPath($path)
                        ->build();
                    $client = $this->createClient();
                    $client->mkcol('dir1/');
                    $client->put('dir1/file1_1', $parameters);
                    $client->put('dir1/file1_2', $parameters);
                    $client->mkcol('dir1/dir1_2/');
                    $client->put('dir1/dir1_2/file1_2_1', $parameters);
                    $client->put('dir1/dir1_2/file1_2_2', $parameters);
                    $client->mkcol('dir2/');
                    $client->put('dir2/file2_1', $parameters);
                    $client->put('dir2/file2_2', $parameters);
                    $client->mkcol('dir2/dir2_2/');
                    $client->put('dir2/dir2_2/file2_2_1', $parameters);
                    $client->put('dir2/dir2_2/file2_2_2', $parameters);
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
                    $parameters = (new PutParametersBuilder())
                        ->setSrcPath($path)
                        ->build();
                    $client = $this->createClient();
                    $client->mkcol('dir1/');
                    $client->put('dir1/file1_1', $parameters);
                    $client->put('dir1/file1_2', $parameters);
                    $client->mkcol('dir1/dir1_2/');
                    $client->put('dir1/dir1_2/file1_2_1', $parameters);
                    $client->put('dir1/dir1_2/file1_2_2', $parameters);
                    $client->mkcol('dir2/');
                    $client->put('dir2/file2_1', $parameters);
                    $client->put('dir2/file2_2', $parameters);
                    $client->mkcol('dir2/dir2_2/');
                    $client->put('dir2/dir2_2/file2_2_1', $parameters);
                    $client->put('dir2/dir2_2/file2_2_2', $parameters);
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
     * @param string|UriInterface   $srcUri
     * @param string|UriInterface   $destUri
     * @param array<string, string> $expected
     * @dataProvider copyProvider
     */
    public function testCopy(callable $before, $srcUri, $destUri, bool $overwrite, array $expected): void
    {
        $before();

        $parameters = (new CopyParametersBuilder())
            ->setDestUrl($destUri)
            ->setOverwrite($overwrite)
            ->build();
        $client = $this->createClient();
        $response = $client->copy($srcUri, $parameters);

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
                    $parameters = (new PutParametersBuilder())
                        ->setSrcPath($path)
                        ->build();
                    $client = $this->createClient();
                    $client->put('file1', $parameters);
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
                    $parameters = (new PutParametersBuilder())
                        ->setSrcPath($path)
                        ->build();
                    $client = $this->createClient();
                    $client->put('file1', $parameters);
                    $client->put('file2', $parameters);
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
                    $parameters = (new PutParametersBuilder())
                        ->setSrcPath($path)
                        ->build();
                    $client = $this->createClient();
                    $client->mkcol('dir1/');
                    $client->put('dir1/file1_1', $parameters);
                    $client->put('dir1/file1_2', $parameters);
                    $client->mkcol('dir1/dir1_2/');
                    $client->put('dir1/dir1_2/file1_2_1', $parameters);
                    $client->put('dir1/dir1_2/file1_2_2', $parameters);
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
                    $parameters = (new PutParametersBuilder())
                        ->setSrcPath($path)
                        ->build();
                    $client = $this->createClient();
                    $client->mkcol('dir1/');
                    $client->put('dir1/file1_1', $parameters);
                    $client->put('dir1/file1_2', $parameters);
                    $client->mkcol('dir1/dir1_2/');
                    $client->put('dir1/dir1_2/file1_2_1', $parameters);
                    $client->put('dir1/dir1_2/file1_2_2', $parameters);
                    $client->mkcol('dir2/');
                    $client->put('dir2/file2_1', $parameters);
                    $client->put('dir2/file2_2', $parameters);
                    $client->mkcol('dir2/dir2_2/');
                    $client->put('dir2/dir2_2/file2_2_1', $parameters);
                    $client->put('dir2/dir2_2/file2_2_2', $parameters);
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
     * @param string|UriInterface   $srcUri
     * @param string|UriInterface   $destUri
     * @param array<string, string> $expected
     * @dataProvider moveProvider
     */
    public function testMove(callable $before, $srcUri, $destUri, array $expected): void
    {
        $before();

        $parameters = (new MoveParametersBuilder())
            ->setDestUrl($destUri)
            ->build();
        $client = $this->createClient();
        $response = $client->move($srcUri, $parameters);

        $this->assertEquals($expected['reason_phrase'], $response->getReasonPhrase());
        $this->assertEquals($expected['status_code'], $response->getStatusCode());
    }

    public function testMkcol(): void
    {
        $client = $this->createClient();

        $response = $client->mkcol('dir/');

        $this->assertEquals('Created', $response->getReasonPhrase());
        $this->assertEquals(201, $response->getStatusCode());
    }

    public function testHeadIfExists(): void
    {
        $client = $this->createClient();

        $file = $this->createTmpFile();
        $path = \stream_get_meta_data($file)['uri'];
        $parameters = (new PutParametersBuilder())
            ->setSrcPath($path)
            ->build();
        $response = $client->put('file', $parameters);

        $response = $client->head('file');

        $this->assertEquals('OK', $response->getReasonPhrase());
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testHeadIfNotExists(): void
    {
        $client = $this->createClient();

        $response = $client->head('file');

        $this->assertEquals('Not Found', $response->getReasonPhrase());
        $this->assertEquals(404, $response->getStatusCode());
    }

    public function testListDirectoryContentsIfDirectoryIsFound(): void
    {
        $client = $this->createClient();

        $file = $this->createTmpFile();
        $path = \stream_get_meta_data($file)['uri'];
        $parameters = (new PutParametersBuilder())
            ->setSrcPath($path)
            ->build();
        $client->put('file', $parameters);
        $client->mkcol('dir/');
        $client->put('dir/file', $parameters);
        $client->mkcol('dir/dir2/');
        $client->put('dir/dir2/file', $parameters);

        $parameters = (new PropfindParametersBuilder())
            ->build();
        $response = $client->propfind('', $parameters);
        $xml = $response->getBodyAsXml();
        $hrefNodes = $xml->getElementsByTagNameNS('DAV:', 'href');

        $this->assertEquals('Multi-Status', $response->getReasonPhrase());
        $this->assertEquals(207, $response->getStatusCode());
        $this->assertEquals($this->webDavBasePath, $hrefNodes->item(0)->nodeValue);
        $this->assertEquals($this->webDavBasePath . 'file', $hrefNodes->item(1)->nodeValue);
        $this->assertEquals($this->webDavBasePath . 'dir/', $hrefNodes->item(2)->nodeValue);

        $response = $client->propfind('dir/', $parameters);
        $xml = $response->getBodyAsXml();
        $hrefNodes = $xml->getElementsByTagNameNS('DAV:', 'href');

        $this->assertEquals('Multi-Status', $response->getReasonPhrase());
        $this->assertEquals(207, $response->getStatusCode());
        $this->assertEquals($this->webDavBasePath . 'dir/', $hrefNodes->item(0)->nodeValue);
        $this->assertEquals($this->webDavBasePath . 'dir/file', $hrefNodes->item(1)->nodeValue);
    }

    public function testListDirectoryContentsIfDirectoryIsNotFound(): void
    {
        $client = $this->createClient();

        $parameters = (new PropfindParametersBuilder())
            ->build();
        $response = $client->propfind('dir/', $parameters);

        $this->assertEquals('Not Found', $response->getReasonPhrase());
        $this->assertEquals(404, $response->getStatusCode());

        $xml = new DOMDocument('1.0', 'utf-8');
        $xml->preserveWhiteSpace = false;
        $xml->formatOutput = true;
        $this->assertEquals($xml, $response->getBodyAsXml());
    }

    public function testProppatch(): void
    {
        $file = $this->createTmpFile();
        $path = \stream_get_meta_data($file)['uri'];
        $parameters = (new PutParametersBuilder())
            ->setSrcPath($path)
            ->build();
        $client = $this->createClient();
        $client->put('file', $parameters);

        $client = $this->createClient();

        $parameters = (new PropfindParametersBuilder())
            ->build();
        $listResponseBefore = $client->propfind('file', $parameters);
        $propertyBefore = $listResponseBefore->getBodyAsXml();

        $this->assertEquals('F', $propertyBefore->getElementsByTagNameNS('http://apache.org/dav/props/', 'executable')->item(0)->nodeValue);

        $propertyBefore->getElementsByTagNameNS('http://apache.org/dav/props/', 'executable')->item(0)->nodeValue = 'T';

        $parameters = (new ProppatchParametersBuilder())
            ->addPropertyToSet($propertyBefore->getElementsByTagNameNS('http://apache.org/dav/props/', 'executable')->item(0))
            ->build();

        $proppatchResponse = $client->proppatch('file', $parameters);

        $this->assertEquals('Multi-Status', $proppatchResponse->getReasonPhrase());
        $this->assertEquals(207, $proppatchResponse->getStatusCode());

        $parameters = (new PropfindParametersBuilder())
            ->build();
        $listResponseAfter = $client->propfind('file', $parameters);
        $propertyAfter = $listResponseAfter->getBodyAsXml();

        $this->assertEquals('T', $propertyAfter->getElementsByTagNameNS('http://apache.org/dav/props/', 'executable')->item(0)->nodeValue);

        $parameters = (new ProppatchParametersBuilder())
            ->addPropertyToRemove($propertyBefore->getElementsByTagNameNS('http://apache.org/dav/props/', 'executable')->item(0))
            ->build();

        $proppatchResponse = $client->proppatch('file', $parameters);

        $this->assertEquals('Multi-Status', $proppatchResponse->getReasonPhrase());
        $this->assertEquals(207, $proppatchResponse->getStatusCode());

        $parameters = (new PropfindParametersBuilder())
            ->build();
        $listResponseAfter = $client->propfind('file', $parameters);
        $propertyAfter = $listResponseAfter->getBodyAsXml();

        $this->assertEquals('T', $propertyAfter->getElementsByTagNameNS('http://apache.org/dav/props/', 'executable')->item(0)->nodeValue);
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
        $parameters = (new PropfindParametersBuilder())
            ->setDepth(1)
            ->build();
        $hrefNodes = $client
            ->propfind($directoryPath, $parameters)
            ->getBodyAsXml()
            ->getElementsByTagNameNS('DAV:', 'href');
        foreach ($hrefNodes as $element) {
            if ($element->nodeValue == $this->webDavBasePath . $directoryPath) {
                continue;
            }
            if (\preg_match("|{$this->webDavBasePath}(.*\/)$|", $element->nodeValue, $matches)) {
                $this->deleteWebDav($matches[1]);
            }
            $client = $this->createClient();
            \assert(!\is_null(\preg_replace("|{$this->webDavBasePath}(.*)|", '\1', $element->nodeValue)));
            $client->delete(\preg_replace("|{$this->webDavBasePath}(.*)|", '\1', $element->nodeValue));
        }
    }
}
