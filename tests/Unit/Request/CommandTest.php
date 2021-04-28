<?php

declare(strict_types=1);

namespace Ngmy\WebDav\Tests\Unit\Request;

use DOMDocument;
use Exception;
use Ngmy\WebDav\Client;
use Ngmy\WebDav\Request;
use Ngmy\WebDav\Tests\TestCase;
use org\bovigo\vfs\vfsStream;
use Psr\Http\Message\UriInterface;
use RuntimeException;

use function get_class;

use const DIRECTORY_SEPARATOR;

class CommandTest extends TestCase
{
    /**
     * @return array<int, array<int, mixed>>
     */
    public function createGetCommandProvider(): array
    {
        return [
            [
                (new Client\Options\Builder())->build(),
                'http://example.com/file',
            ],
        ];
    }

    /**
     * @param string|UriInterface $url
     * @dataProvider createGetCommandProvider
     */
    public function testCreateGetCommand(
        Client\Options $options,
        $url
    ): void {
        $command = Request\Command::createGetCommand($options, $url);

        $this->assertInstanceOf(Request\Command::class, $command);
        $this->assertSame($options, $command->getOptions());
        $this->assertEquals(Request\Method::GET(), $command->getMethod());
        $this->assertEquals(Request\Url::createRequestUrl($url), $command->getUrl());
    }

    /**
     * @return array<int, array<int, mixed>>
     */
    public function createPutCommandProvider(): array
    {
        return [
            [
                function () {
                    $root = vfsStream::setup();
                    vfsStream::newFile('file')->at($root);
                },
                (new Client\Options\Builder())->build(),
                'http://example.com/file',
                (new Request\Parameters\Builder\Put())
                    ->setSourcePath(vfsStream::url('root' . DIRECTORY_SEPARATOR . 'file'))
                    ->build(),
            ],
            [
                function () {
                    vfsStream::setup();
                },
                (new Client\Options\Builder())->build(),
                'http://example.com/file',
                (new Request\Parameters\Builder\Put())
                    ->setSourcePath(vfsStream::url('root' . DIRECTORY_SEPARATOR . 'file'))
                    ->build(),
                new RuntimeException(),
            ]
        ];
    }

    /**
     * @param string|UriInterface $url
     * @dataProvider createPutCommandProvider
     */
    public function testCreatePutCommand(
        callable $before,
        Client\Options $options,
        $url,
        Request\Parameters\Put $parameters,
        Exception $exception = null
    ): void {
        if ($exception instanceof Exception) {
            $this->expectException(get_class($exception));
        }

        $before();

        $command = Request\Command::createPutCommand($options, $url, $parameters);

        $this->assertInstanceOf(Request\Command::class, $command);
        $this->assertSame($options, $command->getOptions());
        $this->assertEquals(Request\Method::PUT(), $command->getMethod());
        $this->assertEquals(Request\Url::createRequestUrl($url), $command->getUrl());
    }

    /**
     * @return array<int, array<int, mixed>>
     */
    public function createDeleteCommandProvider(): array
    {
        return [
            [
                (new Client\Options\Builder())->build(),
                'http://example.com/file',
            ],
        ];
    }

    /**
     * @param string|UriInterface $url
     * @dataProvider createDeleteCommandProvider
     */
    public function testCreateDeleteCommand(
        Client\Options $options,
        $url
    ): void {
        $command = Request\Command::createDeleteCommand($options, $url);

        $this->assertInstanceOf(Request\Command::class, $command);
        $this->assertSame($options, $command->getOptions());
        $this->assertEquals(Request\Method::DELETE(), $command->getMethod());
        $this->assertEquals(Request\Url::createRequestUrl($url), $command->getUrl());
    }

    /**
     * @return array<int, array<int, mixed>>
     */
    public function createCopyCommandProvider(): array
    {
        return [
            [
                (new Client\Options\Builder())->build(),
                'http://example.com/file1',
                (new Request\Parameters\Builder\Copy())->setDestinationUrl('http://example.com/file2')->build()
            ],
        ];
    }

    /**
     * @param string|UriInterface $url
     * @dataProvider createCopyCommandProvider
     */
    public function testCreateCopyCommand(
        Client\Options $options,
        $url,
        Request\Parameters\Copy $parameters
    ): void {
        $command = Request\Command::createCopyCommand($options, $url, $parameters);

        $this->assertInstanceOf(Request\Command::class, $command);
        $this->assertSame($options, $command->getOptions());
        $this->assertEquals(Request\Method::COPY(), $command->getMethod());
        $this->assertEquals(Request\Url::createRequestUrl($url), $command->getUrl());
    }

    /**
     * @return array<int, array<int, mixed>>
     */
    public function createMoveCommandProvider(): array
    {
        return [
            [
                (new Client\Options\Builder())->build(),
                'http://example.com/file1',
                (new Request\Parameters\Builder\Move())->setDestinationUrl('http://example.com/file2')->build()
            ],
        ];
    }

    /**
     * @param string|UriInterface $url
     * @dataProvider createMoveCommandProvider
     */
    public function testCreateMoveCommand(
        Client\Options $options,
        $url,
        Request\Parameters\Move $parameters
    ): void {
        $command = Request\Command::createMoveCommand($options, $url, $parameters);
        $this->assertInstanceOf(Request\Command::class, $command);
        $this->assertSame($options, $command->getOptions());
        $this->assertEquals(Request\Method::MOVE(), $command->getMethod());
        $this->assertEquals(Request\Url::createRequestUrl($url), $command->getUrl());
    }

    /**
     * @return array<int, array<int, mixed>>
     */
    public function createMkcolCommandProvider(): array
    {
        return [
            [
                (new Client\Options\Builder())->build(),
                'http://example.com/dir/',
            ],
        ];
    }

    /**
     * @param string|UriInterface $url
     * @dataProvider createMkcolCommandProvider
     */
    public function testCreateMkcolCommand(
        Client\Options $options,
        $url
    ): void {
        $command = Request\Command::createMkcolCommand($options, $url);

        $this->assertInstanceOf(Request\Command::class, $command);
        $this->assertSame($options, $command->getOptions());
        $this->assertEquals(Request\Method::MKCOL(), $command->getMethod());
        $this->assertEquals(Request\Url::createRequestUrl($url), $command->getUrl());
    }

    /**
     * @return array<int, array<int, mixed>>
     */
    public function createHeadCommandProvider(): array
    {
        return [
            [
                (new Client\Options\Builder())->build(),
                'http://example.com/file',
            ],
        ];
    }

    /**
     * @param string|UriInterface $url
     * @dataProvider createHeadCommandProvider
     */
    public function testCreateHeadCommand(
        Client\Options $options,
        $url
    ): void {
        $command = Request\Command::createHeadCommand($options, $url);

        $this->assertInstanceOf(Request\Command::class, $command);
        $this->assertSame($options, $command->getOptions());
        $this->assertEquals(Request\Method::HEAD(), $command->getMethod());
        $this->assertEquals(Request\Url::createRequestUrl($url), $command->getUrl());
    }

    /**
     * @return array<int, array<int, mixed>>
     */
    public function createPropfindCommandProvider(): array
    {
        return [
            [
                (new Client\Options\Builder())->build(),
                'http://example.com/dir/',
                (new Request\Parameters\Builder\Propfind())->build()
            ],
        ];
    }

    /**
     * @param string|UriInterface $url
     * @dataProvider createPropfindCommandProvider
     */
    public function testCreatePropfindCommand(
        Client\Options $options,
        $url,
        Request\Parameters\Propfind $parameters
    ): void {
        $command = Request\Command::createPropfindCommand($options, $url, $parameters);

        $this->assertInstanceOf(Request\Command::class, $command);
        $this->assertSame($options, $command->getOptions());
        $this->assertEquals(Request\Method::PROPFIND(), $command->getMethod());
        $this->assertEquals(Request\Url::createRequestUrl($url), $command->getUrl());
    }

    /**
     * @return array<int, array<int, mixed>>
     */
    public function createProppatchCommandProvider(): array
    {
        return [
            [
                (new Client\Options\Builder())->build(),
                'http://example.com/file',
                (new Request\Parameters\Builder\Proppatch())
                    ->addPropertyToSet((new DOMDocument('1.0', 'utf-8'))->createElement('foo'))
                    ->build()
            ],
        ];
    }

    /**
     * @param string|UriInterface $url
     * @dataProvider createProppatchCommandProvider
     */
    public function testCreateProppatchCommand(
        Client\Options $options,
        $url,
        Request\Parameters\Proppatch $parameters
    ): void {
        $command = Request\Command::createProppatchCommand($options, $url, $parameters);

        $this->assertInstanceOf(Request\Command::class, $command);
        $this->assertSame($options, $command->getOptions());
        $this->assertEquals(Request\Method::PROPPATCH(), $command->getMethod());
        $this->assertEquals(Request\Url::createRequestUrl($url), $command->getUrl());
    }
}
