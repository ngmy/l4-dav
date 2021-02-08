<?php

declare(strict_types=1);

namespace Ngmy\L4Dav;

use DOMNode;

interface XmlCommandInterface
{
    public function execute(DOMNode $parent): void;
}
