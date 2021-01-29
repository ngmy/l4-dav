<?php

declare(strict_types=1);

namespace Ngmy\L4Dav;

class Credential
{
    /** @var string */
    private $userName;
    /** @var string */
    private $password;

    /**
     * @return void
     */
    public function __construct(string $userName, string $password)
    {
        $this->userName = $userName;
        $this->password = $password;
    }

    public function getUserName(): string
    {
        return $this->userName;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function __toString(): string
    {
        return $this->userName . ':' . $this->password;
    }
}
