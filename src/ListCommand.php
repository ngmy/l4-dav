<?php

declare(strict_types=1);

namespace Ngmy\L4Dav;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriInterface;

class ListCommand extends Command
{
    /** @var ListResponseParser */
    private $parser;

    /**
     * @param string|UriInterface $uri
     * @return void
     */
    public function __construct(WebDavClientOptions $options, $uri)
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
