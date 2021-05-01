<?php

declare(strict_types=1);

namespace Ngmy\WebDav\Request\Parameters;

use Ngmy\WebDav\Request;
use Psr\Http\Message\UriInterface;

use function func_get_args;

/**
 * @phpstan-type ConstructorType = callable(UriInterface, (Request\Header\Overwrite|null)=): self
 *
 * @psalm-type ConstructorType = callable(UriInterface, (Request\Header\Overwrite|null)=): self
 */
final class Copy
{
    /** @var UriInterface */
    private $destinationUrl;
    /** @var Request\Header\Overwrite */
    private $overwrite;

    /**
     * Create a new instance of the builder class.
     *
     * @return Builder\Copy A new instance of the builder class
     */
    public static function createBuilder(): Builder\Copy
    {
        return new Builder\Copy(self::getConstructor());
    }

    public function getDestinationUrl(): UriInterface
    {
        return $this->destinationUrl;
    }

    public function getOverwrite(): Request\Header\Overwrite
    {
        return $this->overwrite;
    }

    /**
     * @phpstan-return ConstructorType
     *
     * @psalm-return ConstructorType
     */
    private static function getConstructor(): callable
    {
        return function (): self {
            /** @psalm-suppress MixedArgument */
            return new self(...func_get_args());
        };
    }

    /**
     * @param UriInterface                  $destinationUrl The destination path of a file
     * @param Request\Header\Overwrite|null $overwrite      Whether to overwrite copy
     */
    private function __construct(UriInterface $destinationUrl, Request\Header\Overwrite $overwrite = null)
    {
        $this->destinationUrl = $destinationUrl;
        $this->overwrite = $overwrite ?? Request\Header\Overwrite::F();
    }
}
