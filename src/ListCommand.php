<?php

declare(strict_types=1);

namespace Ngmy\L4Dav;

use Psr\Http\Message\ResponseInterface;

class ListCommand extends Command
{
    /** @var ListResponseParser */
    private $parser;

    /**
     * @return void
     */
    public function __construct(WebDavClientOptions $options, string $uri)
    {
        parent::__construct($options, 'PROPFIND', $uri, new Headers([
            'Depth' => '1',
        ]));
        $this->parser = new ListResponseParser();
    }

    /**
     * @inheritdoc
     */
    protected function getResponse(): ResponseInterface
    {
        return $this->parser->parse(parent::getResponse());
    }
}
