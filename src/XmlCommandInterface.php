<?php

declare(strict_types=1);

namespace Ngmy\L4Dav;

use SimpleXMLElement;

interface XmlCommandInterface
{
    public function execute(SimpleXMLElement $parent): void;
}
