<?php

declare(strict_types=1);

namespace Ngmy\L4Dav\Tests\Unit;

use Exception;
use InvalidArgumentException;
use Ngmy\L4Dav\Command;
use Ngmy\L4Dav\Tests\TestCase;
use Ngmy\L4Dav\WebDavClientOptionsBuilder;
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
                    (new WebDavClientOptionsBuilder())->build(),
                    'http://example.com/file1',
                    'http://example.com/file2',
                ],
            ],
            [
                [
                    'Delete',
                    (new WebDavClientOptionsBuilder())->build(),
                    'http://example.com/file',
                ],
            ],
            [
                [
                    'Download',
                    (new WebDavClientOptionsBuilder())->build(),
                    'http://example.com/file',
                    '/tmp/file',
                ],
            ],
            [
                [
                    'Exists',
                    (new WebDavClientOptionsBuilder())->build(),
                    'http://example.com/file',
                ],
            ],
            [
                [
                    'List',
                    (new WebDavClientOptionsBuilder())->build(),
                    'http://example.com/dir/',
                ],
            ],
            [
                [
                    'MakeDirectory',
                    (new WebDavClientOptionsBuilder())->build(),
                    'http://example.com/dir/',
                ],
            ],
            [
                [
                    'Move',
                    (new WebDavClientOptionsBuilder())->build(),
                    'http://example.com/file1',
                    'http://example.com/file2',
                ],
            ],
            [
                [
                    'Upload',
                    (new WebDavClientOptionsBuilder())->build(),
                    $file->url(),
                    'http://example.com/file',
                ],
            ],
            [
                [
                    'NotExsitsCommand',
                    (new WebDavClientOptionsBuilder())->build(),
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
