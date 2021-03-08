<?php

declare(strict_types=1);

namespace Ngmy\PhpWebDav;

class Overwrite
{
    private const HEADER_NAME = 'Overwrite';

    /** @var WebDavBool */
    private $overwrite;

    public static function valueOf(string $overwrite): self
    {
        return new self(WebDavBool::valueOf($overwrite));
    }

    public static function getInstance(bool $overwrite): self
    {
        return new self(WebDavBool::getInstance($overwrite));
    }

    public function __toString(): string
    {
        return (string) $this->overwrite;
    }

    public function provide(Headers $headers): Headers
    {
        return $headers->withHeader(self::HEADER_NAME, (string) $this->overwrite);
    }

    private function __construct(WebDavBool $overwrite)
    {
        $this->overwrite = $overwrite;
    }
}
