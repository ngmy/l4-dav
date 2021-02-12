<?php

declare(strict_types=1);

namespace Ngmy\PhpWebDav;

class Overwrite implements WebDavHeaderInterface
{
    private const HEADER_NAME = 'Overwrite';

    /** @var WebDavBool */
    private $overwrite;

    public static function createFromBool(bool $overwrite): self
    {
        return new self(WebDavBool::createFromBool($overwrite));
    }

    public function __construct(WebDavBool $overwrite)
    {
        $this->overwrite = $overwrite;
    }

    public function __toString(): string
    {
        return (string) $this->overwrite;
    }

    public function provide(Headers $headers): Headers
    {
        return $headers->withHeader(self::HEADER_NAME, (string) $this->overwrite);
    }
}
