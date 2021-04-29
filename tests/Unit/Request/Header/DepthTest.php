<?php

namespace Ngmy\WebDav\Tests\Unit\Request\Header;

use Exception;
use InvalidArgumentException;
use Ngmy\WebDav\Request;
use Ngmy\WebDav\Tests\TestCase;

use function assert;
use function get_class;

class DepthTest extends TestCase
{
    /**
     * @return array<int|string, array<int|string, mixed>>
     */
    public function getInstanceProvider(): array
    {
        return [
            [
                '0',
                Request\Header\Depth::ZERO(),
            ],
            [
                '1',
                Request\Header\Depth::ONE(),
            ],
            [
                'infinity',
                Request\Header\Depth::INFINITY(),
            ],
            [
                'invalid',
                new InvalidArgumentException(),
            ],
        ];
    }

    /**
     * @param Exception|Request\Header\Depth $expected
     * @dataProvider getInstanceProvider
     */
    public function testGetInstance(string $value, $expected): void
    {
        if ($expected instanceof Exception) {
            $this->expectException(get_class($expected));
        }

        $actual = Request\Header\Depth::getInstance($value);

        assert($expected instanceof Request\Header\Depth);
        $this->assertEquals($expected, $actual);
    }
}
