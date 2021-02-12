<?php

declare(strict_types=1);

namespace Ngmy\PhpWebDav;

class Overwrite implements WebDavHeaderInterface
{
    /** @var WebDavBool */
    private $overwrite;

    public function __construct(bool $overwrite)
    {
        $this->overwrite = new WebDavBool($overwrite);
    }

    public function __toString(): string
    {
        return (string) $this->overwrite;
    }

    public function provide(Headers $headers): Headers
    {
        return $headers->withHeader('Overwrite', (string) $this->overwrite);
    }
}
