<?php

declare(strict_types=1);

namespace Ngmy\WebDav\Tests\Feature;

use DOMDocument;
use Exception;
use Http\Client\Curl;
use Http\Discovery\Psr17FactoryDiscovery;
use Ngmy\WebDav\Client;
use Ngmy\WebDav\Request;
use Ngmy\WebDav\Tests\TestCase;
use Psr\Http\Message\UriInterface;
use RuntimeException;

use function assert;
use function fclose;
use function file_put_contents;
use function fopen;
use function fwrite;
use function get_class;
use function is_null;
use function is_resource;
use function preg_match;
use function preg_replace;
use function sprintf;
use function stream_get_meta_data;
use function tmpfile;
use function unlink;

use const CURLAUTH_BASIC;
use const CURLAUTH_DIGEST;
use const CURLOPT_HTTPAUTH;
use const CURLOPT_USERPWD;

class ClientTest extends TestCase
{
    /** @var string */
    protected $webDavBasePath = '/webdav_no_auth/';

    public function tearDown(): void
    {
        $this->deleteWebDav();

        parent::tearDown();
    }

    /**
     * @return list<list<mixed>>
     */
    public function putProvider(): array
    {
        return [
            [
                function () {
                    return $this->createTmpFile();
                },
                'file',
                [
                    'reason_phrase' => 'Created',
                    'status_code' => 201,
                ],
            ],
            // TODO これをpropfindすると400になる。なぜ。
            [
                function () {
                    return $this->createTmpFile();
                },
                'dir/',
                [
                    'reason_phrase' => 'Created',
                    'status_code' => 201,
                ],
            ],
            [
                function () {
                    return null;
                },
                'file',
                new RuntimeException()
            ],
        ];
    }

    /**
     * @param string|UriInterface            $url
     * @param array<string, mixed>|Exception $expected
     * @dataProvider putProvider
     */
    public function testPut(callable $before, $url, $expected): void
    {
        if ($expected instanceof Exception) {
            $this->expectException(get_class($expected));
        }

        $file = $before();
        $sourcePath = is_resource($file) ? stream_get_meta_data($file)['uri'] : '';

        $client = $this->createClient();
        $parameters = (new Request\Parameters\Builder\Put())
            ->setSourcePath($sourcePath)
            ->build();
        $response = $client->put($url, $parameters);

        assert(is_array($expected));
        $this->assertEquals($expected['reason_phrase'], $response->getReasonPhrase());
        $this->assertEquals($expected['status_code'], $response->getStatusCode());
    }

    public function testGetFile(): void
    {
        $client = $this->createClient();

        $file = $this->createTmpFile();
        $path = stream_get_meta_data($file)['uri'];

        $parameters = (new Request\Parameters\Builder\Put())
            ->setSourcePath($path)
            ->build();

        $response = $client->put('file', $parameters);

        $file = $this->createTmpFile();
        $path = stream_get_meta_data($file)['uri'];
        unlink($path);

        $response = $client->get('file');

        $this->assertEquals('OK', $response->getReasonPhrase());
        $this->assertEquals(200, $response->getStatusCode());

        file_put_contents($path, $response->getBody());

        $this->assertFileExists($path);

        $file = $this->createTmpFile();
        $path = stream_get_meta_data($file)['uri'];
        unlink($path);

        $response->getBody()->rewind();

        $fh = fopen($path, 'x');
        if ($fh === false) {
            throw new RuntimeException(sprintf('Failed to open the file "%s".', $path));
        }
        $stream = $response->getBody();
        while (!$stream->eof()) {
            fwrite($fh, $stream->read(2048));
        }
        fclose($fh);

        $this->assertFileExists($path);
    }

    /**
     * @return list<list<mixed>>
     */
    public function headProvider(): array
    {
        return [
            [
                function () {
                    $file = $this->createTmpFile();
                    $path = stream_get_meta_data($file)['uri'];
                    $parameters = (new Request\Parameters\Builder\Put())
                        ->setSourcePath($path)
                        ->build();
                    $client = $this->createClient();
                    $client->put('file', $parameters);
                },
                'file',
                [
                    'reason_phrase' => 'OK',
                    'status_code' => 200,
                ],
            ],
            [
                function () {
                    $client = $this->createClient();
                    $client->mkcol('dir/');
                },
                'dir/', // TODO /がないとMoved Permanentlyになる。なぜ
                [
                    'reason_phrase' => 'OK',
                    'status_code' => 200,
                ],
            ],
            [
                function () {
                },
                'file',
                [
                    'reason_phrase' => 'Not Found',
                    'status_code' => 404,
                ],
            ],
            [
                function () {
                },
                'dir/',
                [
                    'reason_phrase' => 'Not Found',
                    'status_code' => 404,
                ],
            ],
        ];
    }

    /**
     * @param string|UriInterface  $url
     * @param array<string, mixed> $expected
     * @dataProvider headProvider
     */
    public function testHead(callable $before, $url, array $expected): void
    {
        $before();

        $client = $this->createClient();

        $response = $client->head($url);

        $this->assertEquals($expected['reason_phrase'], $response->getReasonPhrase());
        $this->assertEquals($expected['status_code'], $response->getStatusCode());
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
                    $path = stream_get_meta_data($file)['uri'];
                    $parameters = (new Request\Parameters\Builder\Put())
                        ->setSourcePath($path)
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
                    $path = stream_get_meta_data($file)['uri'];
                    $parameters = (new Request\Parameters\Builder\Put())
                        ->setSourcePath($path)
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
     * @param string|UriInterface  $url
     * @param array<string, mixed> $expected
     * @dataProvider deleteProvider
     */
    public function testDeleteFile(callable $before, $url, array $expected): void
    {
        $before();

        $client = $this->createClient();
        $response = $client->delete($url);

        $this->assertEquals($expected['reason_phrase'], $response->getReasonPhrase());
        $this->assertEquals($expected['status_code'], $response->getStatusCode());
    }

    /**
     * @return list<list<mixed>>
     */
    public function mkcolProvider(): array
    {
        return [
            [
                'dir/',
                [
                    'reason_phrase' => 'Created',
                    'status_code' => 201,
                ],
            ],
            [
                'dir',
                [
                    'reason_phrase' => 'Created',
                    'status_code' => 201,
                ],
            ],
        ];
    }

    /**
     * @param string|UriInterface  $url
     * @param array<string, mixed> $expected
     * @dataProvider mkcolProvider
     */
    public function testMkcol($url, array $expected): void
    {
        $client = $this->createClient();

        $response = $client->mkcol($url);

        $this->assertEquals($expected['reason_phrase'], $response->getReasonPhrase());
        $this->assertEquals($expected['status_code'], $response->getStatusCode());
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
                    $path = stream_get_meta_data($file)['uri'];
                    $parameters = (new Request\Parameters\Builder\Put())
                        ->setSourcePath($path)
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
                    $path = stream_get_meta_data($file)['uri'];
                    $parameters = (new Request\Parameters\Builder\Put())
                        ->setSourcePath($path)
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
                    $path = stream_get_meta_data($file)['uri'];
                    $parameters = (new Request\Parameters\Builder\Put())
                        ->setSourcePath($path)
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
                    $path = stream_get_meta_data($file)['uri'];
                    $parameters = (new Request\Parameters\Builder\Put())
                        ->setSourcePath($path)
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
                    $path = stream_get_meta_data($file)['uri'];
                    $parameters = (new Request\Parameters\Builder\Put())
                        ->setSourcePath($path)
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
                    $path = stream_get_meta_data($file)['uri'];
                    $parameters = (new Request\Parameters\Builder\Put())
                        ->setSourcePath($path)
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
     * @param string|UriInterface  $sourceUrl
     * @param string|UriInterface  $destinationUrl
     * @param array<string, mixed> $expected
     * @dataProvider copyProvider
     */
    public function testCopy(callable $before, $sourceUrl, $destinationUrl, bool $overwrite, array $expected): void
    {
        $before();

        $client = $this->createClient();
        $parameters = (new Request\Parameters\Builder\Copy())
            ->setDestinationUrl($destinationUrl)
            ->setOverwrite($overwrite)
            ->build();
        $response = $client->copy($sourceUrl, $parameters);

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
                    $path = stream_get_meta_data($file)['uri'];
                    $parameters = (new Request\Parameters\Builder\Put())
                        ->setSourcePath($path)
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
                    $path = stream_get_meta_data($file)['uri'];
                    $parameters = (new Request\Parameters\Builder\Put())
                        ->setSourcePath($path)
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
                    $path = stream_get_meta_data($file)['uri'];
                    $parameters = (new Request\Parameters\Builder\Put())
                        ->setSourcePath($path)
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
                    $path = stream_get_meta_data($file)['uri'];
                    $parameters = (new Request\Parameters\Builder\Put())
                        ->setSourcePath($path)
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
     * @param string|UriInterface  $sourceUrl
     * @param string|UriInterface  $destinationUrl
     * @param array<string, mixed> $expected
     * @dataProvider moveProvider
     */
    public function testMove(callable $before, $sourceUrl, $destinationUrl, array $expected): void
    {
        $before();

        $parameters = (new Request\Parameters\Builder\Move())
            ->setDestinationUrl($destinationUrl)
            ->build();
        $client = $this->createClient();
        $response = $client->move($sourceUrl, $parameters);

        $this->assertEquals($expected['reason_phrase'], $response->getReasonPhrase());
        $this->assertEquals($expected['status_code'], $response->getStatusCode());
    }

    public function testListDirectoryContentsIfDirectoryIsFound(): void
    {
        $client = $this->createClient();

        $file = $this->createTmpFile();
        $path = stream_get_meta_data($file)['uri'];
        $parameters = (new Request\Parameters\Builder\Put())
            ->setSourcePath($path)
            ->build();
        $client->put('file', $parameters);
        $client->mkcol('dir/');
        $client->put('dir/file', $parameters);
        $client->mkcol('dir/dir2/');
        $client->put('dir/dir2/file', $parameters);

        $parameters = (new Request\Parameters\Builder\Propfind())
            ->build();
        $response = $client->propfind('', $parameters);
        $xml = $response->getBodyAsXml();
        $hrefNodes = $xml->getElementsByTagNameNS('DAV:', 'href');

        $this->assertEquals('Multi-Status', $response->getReasonPhrase());
        $this->assertEquals(207, $response->getStatusCode());
        assert(!is_null($hrefNodes->item(0)));
        assert(!is_null($hrefNodes->item(1)));
        assert(!is_null($hrefNodes->item(2)));
        $this->assertEquals($this->webDavBasePath, $hrefNodes->item(0)->nodeValue);
        $this->assertEquals($this->webDavBasePath . 'file', $hrefNodes->item(1)->nodeValue);
        $this->assertEquals($this->webDavBasePath . 'dir/', $hrefNodes->item(2)->nodeValue);

        $response = $client->propfind('dir/', $parameters);
        $xml = $response->getBodyAsXml();
        $hrefNodes = $xml->getElementsByTagNameNS('DAV:', 'href');

        $this->assertEquals('Multi-Status', $response->getReasonPhrase());
        $this->assertEquals(207, $response->getStatusCode());
        assert(!is_null($hrefNodes->item(0)));
        assert(!is_null($hrefNodes->item(1)));
        $this->assertEquals($this->webDavBasePath . 'dir/', $hrefNodes->item(0)->nodeValue);
        $this->assertEquals($this->webDavBasePath . 'dir/file', $hrefNodes->item(1)->nodeValue);
    }

    public function testListDirectoryContentsIfDirectoryIsNotFound(): void
    {
        $client = $this->createClient();

        $parameters = (new Request\Parameters\Builder\Propfind())
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
        $path = stream_get_meta_data($file)['uri'];
        $parameters = (new Request\Parameters\Builder\Put())
            ->setSourcePath($path)
            ->build();
        $client = $this->createClient();
        $client->put('file', $parameters);

        $client = $this->createClient();

        $parameters = (new Request\Parameters\Builder\Propfind())
            ->build();
        $listResponseBefore = $client->propfind('file', $parameters);
        $propertyBefore = $listResponseBefore->getBodyAsXml();
        $executableBefore = $propertyBefore
            ->getElementsByTagNameNS('http://apache.org/dav/props/', 'executable')
            ->item(0);

        assert(!is_null($executableBefore));
        $this->assertEquals('F', $executableBefore->nodeValue);

        $executableBefore->nodeValue = 'T';

        $parameters = (new Request\Parameters\Builder\Proppatch())
            ->addPropertyToSet($executableBefore)
            ->build();

        $proppatchResponse = $client->proppatch('file', $parameters);

        $this->assertEquals('Multi-Status', $proppatchResponse->getReasonPhrase());
        $this->assertEquals(207, $proppatchResponse->getStatusCode());

        $parameters = (new Request\Parameters\Builder\Propfind())
            ->build();
        $listResponseAfter = $client->propfind('file', $parameters);
        $propertyAfter = $listResponseAfter->getBodyAsXml();
        $executableAfter = $propertyAfter
            ->getElementsByTagNameNS('http://apache.org/dav/props/', 'executable')
            ->item(0);

        assert(!is_null($executableAfter));
        $this->assertEquals('T', $executableAfter->nodeValue);

        $parameters = (new Request\Parameters\Builder\Proppatch())
            ->addPropertyToRemove($executableAfter)
            ->build();

        $proppatchResponse = $client->proppatch('file', $parameters);

        $this->assertEquals('Multi-Status', $proppatchResponse->getReasonPhrase());
        $this->assertEquals(207, $proppatchResponse->getStatusCode());

        $parameters = (new Request\Parameters\Builder\Propfind())
            ->build();
        $listResponseAfter = $client->propfind('file', $parameters);
        $propertyAfter = $listResponseAfter->getBodyAsXml();
        $executableAfter = $propertyAfter
            ->getElementsByTagNameNS('http://apache.org/dav/props/', 'executable')
            ->item(0);

        assert(!is_null($executableAfter));
        $this->assertEquals('T', $executableAfter->nodeValue);
    }

    protected function createClient(): Client
    {
        $curlOptions = [];
        if (isset($this->webDavAuthType)) {
            assert(isset($this->webDavUserName));
            assert(isset($this->webDavPassword));
            $curlOptions[CURLOPT_USERPWD] = $this->webDavUserName . ':' . $this->webDavPassword;
            if ($this->webDavAuthType == 'basic') {
                $curlOptions[CURLOPT_HTTPAUTH] = CURLAUTH_BASIC;
            }
            if ($this->webDavAuthType == 'digest') {
                $curlOptions[CURLOPT_HTTPAUTH] = CURLAUTH_DIGEST;
            }
        }
        $httpClient = new Curl\Client(
            Psr17FactoryDiscovery::findResponseFactory(),
            Psr17FactoryDiscovery::findStreamFactory(),
            $curlOptions
        );
        $optionsBuilder = (new Client\Options\Builder())
            ->setBaseUrl('http://apache2' . $this->webDavBasePath);
        $options = $optionsBuilder->build();
        return new Client($httpClient, $options);
    }

    /**
     * @throws RuntimeException
     * @return resource
     */
    protected function createTmpFile()
    {
        $file = tmpfile();
        if ($file === false) {
            throw new RuntimeException('Failed to create a temporary file.');
        }
        fwrite($file, 'This is test file.');
        return $file;
    }

    protected function deleteWebDav(string $directoryPath = ''): void
    {
        $client = $this->createClient();
        $parameters = (new Request\Parameters\Builder\Propfind())
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
            if (preg_match("~{$this->webDavBasePath}(.*\/)$~", $element->nodeValue, $matches)) {
                $this->deleteWebDav($matches[1]);
            }
            $client = $this->createClient();
            assert(!is_null(preg_replace("~{$this->webDavBasePath}(.*)~", '\1', $element->nodeValue)));
            $client->delete(preg_replace("~{$this->webDavBasePath}(.*)~", '\1', $element->nodeValue));
        }
    }
}
