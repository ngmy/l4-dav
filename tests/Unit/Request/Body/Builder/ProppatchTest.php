<?php

declare(strict_types=1);

namespace Ngmy\WebDav\Tests\Unit\Request\Body\Builder;

use DOMDocument;
use Exception;
use Http\Discovery\Psr17FactoryDiscovery;
use InvalidArgumentException;
use Mockery;
use Ngmy\WebDav\Request;
use Ngmy\WebDav\Tests\TestCase;
use ReflectionObject;

use function assert;
use function get_class;

class ProppatchTest extends TestCase
{
    /**
     * @return list<list<mixed>>
     */
    public function buildProvider(): array
    {
        return [
            [
                [],
                [],
                new InvalidArgumentException(),
            ],
            [
                [],
                [],
                new Request\Body(
                    Psr17FactoryDiscovery::findStreamFactory()->createStream(
                        <<<XML
<?xml version="1.0" encoding="utf-8"?>
<D:propertyupdate xmlns:D="DAV:"/>

XML
                    )
                ),
            ],
            [
                [
                    (function () {
                        $xml = new DOMDocument('1.0', 'utf-8');
                        $xml->preserveWhiteSpace = false;
                        $xml->formatOutput = true;
                        $xml->appendChild($xml->createElementNS('DAV:', 'D:propertyupdate'));
                        return $xml->createElement('foo');
                    })(),
                ],
                [],
                new Request\Body(
                    Psr17FactoryDiscovery::findStreamFactory()->createStream(
                        <<<XML
<?xml version="1.0" encoding="utf-8"?>
<D:propertyupdate xmlns:D="DAV:">
  <D:set>
    <D:prop>
      <foo/>
    </D:prop>
  </D:set>
</D:propertyupdate>

XML
                    )
                ),
            ],
            [
                [],
                [
                    (function () {
                        $xml = new DOMDocument('1.0', 'utf-8');
                        $xml->preserveWhiteSpace = false;
                        $xml->formatOutput = true;
                        $xml->appendChild($xml->createElementNS('DAV:', 'D:propertyupdate'));
                        return $xml->createElement('bar');
                    })(),
                ],
                new Request\Body(
                    Psr17FactoryDiscovery::findStreamFactory()->createStream(
                        <<<XML
<?xml version="1.0" encoding="utf-8"?>
<D:propertyupdate xmlns:D="DAV:">
  <D:remove>
    <D:prop>
      <bar/>
    </D:prop>
  </D:remove>
</D:propertyupdate>

XML
                    )
                ),
            ],
            [
                [
                    (function () {
                        $xml = new DOMDocument('1.0', 'utf-8');
                        $xml->preserveWhiteSpace = false;
                        $xml->formatOutput = true;
                        $xml->appendChild($xml->createElementNS('DAV:', 'D:propertyupdate'));
                        return $xml->createElement('foo1');
                    })(),
                    (function () {
                        $xml = new DOMDocument('1.0', 'utf-8');
                        $xml->preserveWhiteSpace = false;
                        $xml->formatOutput = true;
                        $xml->appendChild($xml->createElementNS('DAV:', 'D:propertyupdate'));
                        return $xml->createElement('foo2');
                    })(),
                ],
                [
                    (function () {
                        $xml = new DOMDocument('1.0', 'utf-8');
                        $xml->preserveWhiteSpace = false;
                        $xml->formatOutput = true;
                        $xml->appendChild($xml->createElementNS('DAV:', 'D:propertyupdate'));
                        return $xml->createElement('bar1');
                    })(),
                    (function () {
                        $xml = new DOMDocument('1.0', 'utf-8');
                        $xml->preserveWhiteSpace = false;
                        $xml->formatOutput = true;
                        $xml->appendChild($xml->createElementNS('DAV:', 'D:propertyupdate'));
                        return $xml->createElement('bar2');
                    })(),
                ],
                new Request\Body(
                    Psr17FactoryDiscovery::findStreamFactory()->createStream(
                        <<<XML
<?xml version="1.0" encoding="utf-8"?>
<D:propertyupdate xmlns:D="DAV:">
  <D:set>
    <D:prop>
      <foo1/>
      <foo2/>
    </D:prop>
  </D:set>
  <D:remove>
    <D:prop>
      <bar1/>
      <bar2/>
    </D:prop>
  </D:remove>
</D:propertyupdate>

XML
                    )
                ),
            ],
        ];
    }

    /**
     * @param array<int, DOMDocument> $propertiesToSet
     * @param array<int, DOMDocument> $propertiesToRemove
     * @param Exception|Request\Body  $expected
     * @dataProvider buildProvider
     */
    public function testBuild(array $propertiesToSet, array $propertiesToRemove, $expected): void
    {
        $builder = new Request\Body\Builder\Proppatch();

        if ($expected instanceof Exception) {
            $this->expectException(get_class($expected));

            $xml = Mockery::mock(DOMDocument::class);
            $xml->shouldReceive('saveXml')->andReturn(false);

            $reflectBuilderXml = (new ReflectionObject($builder))->getProperty('xml');
            $reflectBuilderXml->setAccessible(true);
            $reflectBuilderXml->setValue($builder, $xml);
        }

        foreach ($propertiesToSet as $property) {
            $builder->addPropetyToSet($property);
        }
        foreach ($propertiesToRemove as $property) {
            $builder->addPropetyToRemove($property);
        }
        $actual = $builder->build();

        assert($expected instanceof Request\Body);

        $reflectExpectedBody = (new ReflectionObject($expected))->getProperty('body');
        $reflectExpectedBody->setAccessible(true);
        $reflectActualBody = (new ReflectionObject($actual))->getProperty('body');
        $reflectActualBody->setAccessible(true);

        $this->assertSame(
            (string) $reflectExpectedBody->getValue($expected),
            (string) $reflectActualBody->getValue($actual)
        );
    }
}
