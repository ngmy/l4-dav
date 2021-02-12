<?php

declare(strict_types=1);

namespace Ngmy\PhpWebDav;

interface WebDavHeaderInterface
{
    public function provide(Headers $headers): Headers;
}
