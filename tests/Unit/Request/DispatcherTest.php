<?php

declare(strict_types=1);

namespace Ngmy\WebDav\Tests\Unit\Request;

use Mockery;
use Ngmy\WebDav\Client;
use Ngmy\WebDav\Request;
use Ngmy\WebDav\Tests\TestCase;
use org\bovigo\vfs\vfsStream;
use Psr\Http\Client\ClientInterface as HttpClientInterface;
use Psr\Http\Message\ResponseInterface;

class DispatcherTest extends TestCase
{
    /**
     * @return list<list<mixed>>
     */
    public function dispatchProvider(): array
    {
        $root = vfsStream::setup();
        $file = vfsStream::newFile('file')->at($root);

        return [
            [
                Request\Command::createGetCommand(
                    (new Client\Options\Builder())->build(),
                    'http://example.com/file'
                ),
                ResponseInterface::class,
            ],
            [
                Request\Command::createPutCommand(
                    (new Client\Options\Builder())->build(),
                    'http://example.com/file',
                    (new Request\Parameters\Builder\Put())->setSourcePath($file->url())->build()
                ),
                ResponseInterface::class,
            ],
        ];
    }

    /**
     * @param class-string<object> $expected
     * @dataProvider dispatchProvider
     */
    public function testDispatch(Request\Command $command, string $expected): void
    {
        $response = Mockery::mock($expected);
        $client = Mockery::mock(HttpClientInterface::class);
        $client->shouldReceive('sendRequest')->andReturn($response);
        $dispatcher = new Request\Dispatcher($client);
        $actual = $dispatcher->dispatch($command);
        $this->assertInstanceOf($expected, $actual);
    }
}
