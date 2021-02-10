<?php

declare(strict_types=1);

namespace Ngmy\PhpWebDav;

use DOMNode;

interface XmlCommandInterface
{
    public function execute(DOMNode $parent): void;
}
