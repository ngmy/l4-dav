<?php

declare(strict_types=1);

namespace Ngmy\WebDav\Request\Body\Builder\Instruction;

use DOMDocument;
use DOMElement;
use Ngmy\Enum\Enum;

use function sprintf;

/**
 * @method static self SET()
 * @method static self REMOVE()
 */
class Proppatch extends Enum
{
    /**
     * @var string
     * @enum
     */
    private static $SET = 'set';
    /**
     * @var string
     * @enum
     */
    private static $REMOVE = 'remove';

    public function getValue(): string
    {
        return self::${$this->name()};
    }

    public function provide(DOMDocument $xml): DOMElement
    {
        return $xml->createElementNS('DAV:', sprintf('D:%s', $this->getValue()));
    }
}
