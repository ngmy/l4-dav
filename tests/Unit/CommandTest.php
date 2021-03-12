<?php

declare(strict_types=1);

namespace Ngmy\WebDav\Tests\Unit;

use Exception;
use InvalidArgumentException;
use Ngmy\WebDav\Command;
use Ngmy\WebDav\Tests\TestCase;
use Ngmy\WebDav\ClientOptionsBuilder;
use org\bovigo\vfs\vfsStream;

class CommandTest extends TestCase
{
    /**
     * @return list<list<mixed>>
     */
    public function createProvider(): array
    {
        $root = vfsStream::setup();
        $file = vfsStream::newFile('file')->at($root);

        return [
            [
                [
                    'Copy',
                    (new ClientOptionsBuilder())->build(),
                    'http://example.com/file1',
                    'http://example.com/file2',
                ],
            ],
            [
                [
                    'Delete',
                    (new ClientOptionsBuilder())->build(),
                    'http://example.com/file',
                ],
            ],
            [
                [
                    'Download',
                    (new ClientOptionsBuilder())->build(),
                    'http://example.com/file',
                    '/tmp/file',
                ],
            ],
            [
                [
                    'Exists',
                    (new ClientOptionsBuilder())->build(),
                    'http://example.com/file',
                ],
            ],
            [
                [
                    'List',
                    (new ClientOptionsBuilder())->build(),
                    'http://example.com/dir/',
                ],
            ],
            [
                [
                    'MakeDirectory',
                    (new ClientOptionsBuilder())->build(),
                    'http://example.com/dir/',
                ],
            ],
            [
                [
                    'Move',
                    (new ClientOptionsBuilder())->build(),
                    'http://example.com/file1',
                    'http://example.com/file2',
                ],
            ],
            [
                [
                    'Upload',
                    (new ClientOptionsBuilder())->build(),
                    $file->url(),
                    'http://example.com/file',
                ],
            ],
            [
                [
                    'NotExsitsCommand',
                    (new ClientOptionsBuilder())->build(),
                ],
                new InvalidArgumentException(),
            ],
        ];
    }

    /**
     * @param array<int, mixed> $args
     * @param Exception         $expected
     * @dataProvider createProvider
     */
    public function testCreate(array $args, $expected = null): void
    {
        if (\is_null($expected)) {
            $this->expectNotToPerformAssertions();
        }
        if ($expected instanceof Exception) {
            $this->expectException(\get_class($expected));
        }
        Command::create(...$args);
    }
}
