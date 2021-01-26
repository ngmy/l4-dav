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
     * @param string $userName
     * @param string $password
     * @return void
     */
    public function __construct(string $userName, string $password)
    {
        $this->userName = $userName;
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function getUserName(): string
    {
        return $this->userName;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->userName . ':' . $this->password;
    }
}
