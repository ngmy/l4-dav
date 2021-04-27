<?php

declare(strict_types=1);

namespace Ngmy\WebDav\Tests\Unit;

use DOMDocument;
use Http\Discovery\Psr17FactoryDiscovery;
use Ngmy\WebDav\Response;
use Ngmy\WebDav\Tests\TestCase;
use Nyholm\Psr7\Response as Psr7Response;

use function compact;
use function extract;
use function get_class;

class ResponseTest extends TestCase
{
    public function testGetProtocolVersion(): void
    {
        $version = '1.1';
        $response = $this->createResponse(compact('version'));
        $actual = $response->getProtocolVersion();
        $this->assertSame($version, $actual);
    }

    public function testWithProtocolVersion(): void
    {
        $response = $this->createResponse();
        $version = '1.0';
        $actual = $response->withProtocolVersion($version);
        $this->assertInstanceOf(get_class($response), $actual);
        $this->assertSame($version, $actual->getProtocolVersion());
    }

    public function testGetHeaders(): void
    {
        $header = 'header1';
        $value = 'value1';
        $headers = [$header => $value];
        $response = $this->createResponse(compact('headers'));
        $actual = $response->getHeaders();
        $this->assertSame([$header => [$value]], $actual);
    }

    /**
     * @return array<int|string, array<int|string, mixed>>
     */
    public function hasHeadersProvider(): array
    {
        return [
            [
                ['header1' => 'value1'],
                'header1',
                true
            ],
            [
                ['header1' => 'value1'],
                'header2',
                false
            ],
        ];
    }

    /**
     * @param array<string, array<int, string>|string> $headers
     * @dataProvider hasHeadersProvider
     */
    public function testHasHeaders(array $headers, string $header, bool $expected): void
    {
        $response = $this->createResponse(compact('headers'));
        $actual = $response->hasHeader($header);
        $this->assertSame($expected, $actual);
    }

    public function testGetHeader(): void
    {
        $header = 'header';
        $value = 'value';
        $headers = [$header => $value];
        $response = $this->createResponse(compact('headers'));
        $actual = $response->getHeader($header);
        $this->assertSame([$value], $actual);
    }

    public function testGetHeaderLine(): void
    {
        $header = 'header';
        $value = 'value';
        $headers = [$header => $value];
        $response = $this->createResponse(compact('headers'));
        $actual = $response->getHeaderLine($header);
        $this->assertSame($value, $actual);
    }

    public function testWithHeader(): void
    {
        $response = $this->createResponse();
        $header = 'header';
        $value = 'value';
        $actual = $response->withHeader($header, $value);
        $this->assertInstanceOf(get_class($response), $actual);
        $this->assertSame([$header => [$value]], $actual->getHeaders());
    }

    public function testWithAddedHeader(): void
    {
        $header1 = 'header1';
        $value1 = 'value1';
        $headers = [$header1 => $value1];
        $response = $this->createResponse(compact('headers'));
        $header2 = 'header2';
        $value2 = 'value2';
        $actual = $response->withAddedHeader($header2, $value2);
        $this->assertInstanceOf(get_class($response), $actual);
        $this->assertSame([$header1 => [$value1], $header2 => [$value2]], $actual->getHeaders());
    }

    public function testWithoutHeader(): void
    {
        $header = 'header';
        $value = 'value';
        $headers = [$header => $value];
        $response = $this->createResponse(compact('headers'));
        $actual = $response->withoutHeader($header);
        $this->assertInstanceOf(get_class($response), $actual);
        $this->assertSame([], $actual->getHeaders());
    }

    public function testGetBody(): void
    {
        $body = Psr17FactoryDiscovery::findStreamFactory()->createStream('This is the response body');
        $response = $this->createResponse(compact('body'));
        $actual = $response->getBody();
        $this->assertSame($body, $actual);
    }

    public function testWithBody(): void
    {
        $response = $this->createResponse();
        $body = Psr17FactoryDiscovery::findStreamFactory()->createStream('This is the response body');
        $actual = $response->withBody($body);
        $this->assertInstanceOf(get_class($response), $actual);
        $this->assertSame($body, $actual->getBody());
    }

    public function testGetStatusCode(): void
    {
        $status = 200;
        $response = $this->createResponse(compact('status'));
        $actual = $response->getStatusCode();
        $this->assertSame($status, $actual);
    }

    public function testGetReasonPhrase(): void
    {
        $reason = 'OK';
        $response = $this->createResponse(compact('reason'));
        $actual = $response->getReasonPhrase();
        $this->assertSame($reason, $actual);
    }

    public function testWithStatus(): void
    {
        $response = $this->createResponse();
        $code = 404;
        $reason = 'Not Found';
        $actual = $response->withStatus($code, $reason);
        $this->assertInstanceOf(get_class($response), $actual);
        $this->assertSame($code, $actual->getStatusCode());
        $this->assertSame($reason, $actual->getReasonPhrase());
    }

    public function testGetBodyAsXml(): void
    {
        $headers = ['Content-Type' => 'text/xml'];
        $body = Psr17FactoryDiscovery::findStreamFactory()->createStream('<p>This is the response body</p>');
        $response = $this->createResponse(compact('headers', 'body'));
        $actual = $response->getBodyAsXml();
        $this->assertInstanceOf(DOMDocument::class, $actual);
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
}
