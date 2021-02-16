<?php

declare(strict_types=1);

namespace Ngmy\PhpWebDav;

use InvalidArgumentException;

class AuthType
{
    private const ENUM_NONE = 'none';
    private const ENUM_BASIC = 'basic';
    private const ENUM_DIGEST = 'digest';

    /** @var string */
    private $authType;

    public static function createNoneAuthType(): self
    {
        return new self(self::ENUM_NONE);
    }

    public static function createBasicAuthType(): self
    {
        return new self(self::ENUM_BASIC);
    }

    public static function createDigestAuthType(): self
    {
        return new self(self::ENUM_DIGEST);
    }

    public function __construct(string $authType)
    {
        $this->authType = $authType;
        $this->validate();
    }

    public function __toString(): string
    {
        return $this->authType;
    }

    /**
     * @param array<int, mixed> $curlOptions
     * @return array<int, mixed>
     */
    public function provide(array $curlOptions): array
    {
        if ($this->authType == self::ENUM_BASIC) {
            $curlOptions[\CURLOPT_HTTPAUTH] = \CURLAUTH_BASIC;
        }
        if ($this->authType == self::ENUM_DIGEST) {
            $curlOptions[\CURLOPT_HTTPAUTH] = \CURLAUTH_DIGEST;
        }
        return $curlOptions;
    }

    private function validate(): void
    {
        if (
            !\in_array($this->authType, [
                self::ENUM_NONE,
                self::ENUM_BASIC,
                self::ENUM_DIGEST,
            ], true)
        ) {
            throw new InvalidArgumentException(
                \sprintf('The authType must be "none", "basic", or "digest", "%s" given.', $this->authType)
            );
        }
    }
}
