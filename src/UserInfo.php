<?php

declare(strict_types=1);

namespace Ngmy\PhpWebDav;

use League\Uri\Components\UserInfo as LeagueUserInfo;

class UserInfo
{
    /** @var LeagueUserInfo */
    private $userInfo;

    public static function createFromUserNameAndPassword(?string $userName = null, ?string $password = null): self
    {
        return new self(new LeagueUserInfo($userName, $password));
    }

    public function __construct(LeagueUserInfo $userInfo)
    {
        $this->userInfo = $userInfo;
    }

    public function getUserName(): ?string
    {
        return $this->userInfo->getUser();
    }

    public function getPassword(): ?string
    {
        return $this->userInfo->getPass();
    }

    public function withUserNameAndPassword(?string $userName = null, ?string $password = null): self
    {
        return new self(new LeagueUserInfo($userName, $password));
    }

    /**
     * @param array<int, mixed> $curlOptions
     * @return array<int, mixed>
     */
    public function provide(array $curlOptions): array
    {
        if (!empty((string) $this->userInfo)) {
            $curlOptions[\CURLOPT_USERPWD] = (string) $this->userInfo;
        }
        return $curlOptions;
    }
}
