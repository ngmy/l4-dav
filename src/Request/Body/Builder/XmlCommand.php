<?php

declare(strict_types=1);

namespace Ngmy\WebDav\Request\Body\Builder;

use DOMNode;

interface XmlCommand
{
    public function execute(DOMNode $parent): void;
}
