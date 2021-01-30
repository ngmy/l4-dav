<?php

declare(strict_types=1);

namespace Ngmy\L4Dav;

use Psr\Http\Message\ResponseInterface;

class ListResponse implements ResponseInterface
{
    use ResponseTrait;

    /** @var list<string> */
    private $list;

    /**
     * @param list<string> $list
     */
    public function __construct(ResponseInterface $response, array $list)
    {
        $this->response = $response;
        $this->list = $list;
    }

    /**
     * @return list<string>
     */
    public function getList(): array
    {
        return $this->list;
    }
}
