<?php

declare(strict_types=1);

namespace Ngmy\L4Dav\Tests\Unit;

use Ngmy\L4Dav\Headers;
use Ngmy\L4Dav\Tests\TestCase;

class HeadersTest extends TestCase
{
    /**
     * @return list<list<mixed>>
     */
    public function instantiateClassProvider(): array
    {
        return [
            [[]],
            [['key' => 'value']],
            [['key' => ['value1', 'value2']]],
        ];
    }

    /**
     * @param array<string, list<string>|string> $headers
     * @dataProvider instantiateClassProvider
     */
    public function testInstantiateClass(array $headers): void
    {
        $this->expectNotToPerformAssertions();
        new Headers($headers);
    }

    /**
     * @return list<list<mixed>>
     */
    public function withHeaderProvider(): array
    {
        return [
            [
                new Headers(),
                ['key', 'value'],
                new Headers([
                    'key' => 'value',
                ]),
            ],
            [
                new Headers([
                    'key1' => 'value1',
                ]),
                ['key2', 'value2'],
                new Headers([
                    'key1' => 'value1',
                    'key2' => 'value2',
                ]),
            ],
            [
                new Headers([
                    'key' => 'value',
                ]),
                ['key', 'value\''],
                new Headers([
                    'key' => 'value\'',
                ]),
            ],
        ];
    }

    /**
     * @param list<string> $withHeader
     * @dataProvider withHeaderProvider
     */
    public function testWithHeader(Headers $headers, array $withHeader, Headers $expected): void
    {
        $actual = $headers->withHeader(...$withHeader);
        $this->assertEquals($expected, $actual);
    }

    /**
     * @return list<list<mixed>>
     */
    public function withHeadersProvider(): array
    {
        return [
            [
                new Headers(),
                new Headers([
                    'key' => 'value',
                ]),
                new Headers([
                    'key' => 'value',
                ]),
            ],
            [
                new Headers([
                    'key1' => 'value1',
                ]),
                new Headers([
                    'key2' => 'value2',
                ]),
                new Headers([
                    'key1' => 'value1',
                    'key2' => 'value2',
                ]),
            ],
            [
                new Headers([
                    'key1' => 'value1',
                    'key2' => 'value2',
                ]),
                new Headers([
                    'key2' => 'value2\'',
                    'key3' => 'value3',
                ]),
                new Headers([
                    'key1' => 'value1',
                    'key2' => 'value2\'',
                    'key3' => 'value3',
                ]),
            ],
        ];
    }

    /**
     * @dataProvider withHeadersProvider
     */
    public function testWithHeaders(Headers $headers, Headers $withHeaders, Headers $expected): void
    {
        $actual = $headers->withHeaders($withHeaders);
        $this->assertEquals($expected, $actual);
    }

    public function testToArray(): void
    {
        $expected = ['key' => ['value']];
        $actual = (new Headers(['key' => 'value']))->toArray();
        $this->assertEquals($expected, $actual);
    }
}
