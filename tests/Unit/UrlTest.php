<?php

declare(strict_types=1);

namespace Ngmy\L4Dav\Tests\Unit;

use InvalidArgumentException;
use Ngmy\L4Dav\Tests\TestCase;
use Ngmy\L4Dav\Url;

class UrlTest extends TestCase
{
    public function testInvaliUrl(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new Url('invalid_url');
    }

    public function testValue(): void
    {
        $actual = (new Url('http://localhost/webdav/'))->value();
        $expected = 'http://localhost/webdav/';
        $this->assertEquals($expected, $actual);
    }

    public function testWithoutPort(): void
    {
        $actual = (new Url('http://localhost:80/webdav/'))->withoutPort();
        $expected = new Url('http://localhost/webdav/');
        $this->assertEquals($expected, $actual);
    }

    public function testWithPath(): void
    {
        $actual = (new Url('http://localhost/webdav/'))->withPath('dir/file');
        $expected = new Url('http://localhost/webdav/dir/file');
        $this->assertEquals($expected, $actual);
    }

    /**
     * @return array<mixed>
     */
    public function parseProvider(): array
    {
        return [
            [
                'http://localhost/',
                [
                    'scheme' => 'http',
                    'host'   => 'localhost',
                    'path'   => '/',
                ],
            ],
            [
                'http://localhost/webdav/',
                [
                    'scheme' => 'http',
                    'host'   => 'localhost',
                    'path'   => '/webdav/',
                ],
            ],
            [
                'http://localhost:80/webdav/',
                [
                    'scheme' => 'http',
                    'host'   => 'localhost',
                    'port'   => 80,
                    'path'   => '/webdav/',
                ],
            ],
        ];
    }

    /**
     * @dataProvider parseProvider
     * @param array<mixed> $expected
     */
    public function testParse(string $url, array $expected): void
    {
        $actual = (new Url($url))->parse();
        $this->assertEquals($expected, $actual);
    }
}
