<?php

declare(strict_types=1);

namespace Ngmy\WebDav\Tests\Unit\Response\Body\Parser;

use DOMDocument;
use Exception;
use Http\Discovery\Psr17FactoryDiscovery;
use Ngmy\WebDav\Response;
use Ngmy\WebDav\Tests\TestCase;
use Nyholm\Psr7\Response as Psr7Response;
use Psr\Http\Message\StreamInterface;
use RuntimeException;

use function assert;
use function compact;
use function extract;
use function get_class;

class XmlTest extends TestCase
{
    /**
     * @return array<int|string, array<int|string, mixed>>
     */
    public function parseProvider(): array
    {
        return [
            [
                [],
                Psr17FactoryDiscovery::findStreamFactory()->createStream('This is the response body'),
                $this->createXml(),
            ],
            [
                ['Content-Type' => 'text/xml'],
                Psr17FactoryDiscovery::findStreamFactory()->createStream('This is the response body'),
                new RuntimeException(),
            ],
            [
                ['Content-Type' => 'text/xml'],
                Psr17FactoryDiscovery::findStreamFactory()->createStream('<p>This is the response body</p>'),
                (function () {
                    $xml = $this->createXml();
                    $xml->appendChild($xml->createElement('p', 'This is the response body'));
                    return $xml;
                })(),
            ],
        ];
    }

    /**
     * @param array<string, array<int, string>|string> $headers
     * @param DOMDocument|Exception                    $expected
     * @dataProvider parseProvider
     */
    public function testParse(array $headers, StreamInterface $body, $expected): void
    {
        if ($expected instanceof Exception) {
            $this->expectException(get_class($expected));
        }

        $response = $this->createResponse(compact('headers', 'body'));
        $parser = new Response\Body\Parser\Xml($response);
        $actual = $parser->parse();

        assert($expected instanceof DOMDocument);
        $this->assertEquals($expected, $actual);
    }

    /**
     * @param array<string, mixed> $params
     */
    private function createResponse(array $params = []): Response
    {
        $status = 200;
        $headers = [];
        $body = null;
        $version = '1.1';
        $reason = null;

        extract($params);

        return new Response(
            new Psr7Response(
                $status,
                $headers,
                $body,
                $version,
                $reason
            )
        );
    }

    private function createXml(): DOMDocument
    {
        $xml = new DOMDocument('1.0', 'utf-8');
        $xml->preserveWhiteSpace = false;
        $xml->formatOutput = true;

        return $xml;
    }
}
