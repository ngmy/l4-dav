<?php

declare(strict_types=1);

namespace Ngmy\L4Dav;

use Psr\Http\Message\UriInterface;

class ProppatchCommand extends Command
{
    /** @var ProppatchParameters */
    protected $parameters;

    /**
     * @param string|UriInterface $url
     */
    protected function __construct($url, ProppatchParameters $parameters, WebDavClientOptions $options)
    {
        parent::__construct('PROPPATCH', $url, $options, new Headers(), $this->configureBody($parameters));
        $this->parameters = $parameters;
    }

    private function configureBody(ProppatchParameters $parameters): string
    {
        $builder = new ProppatchRequestBodyBuilder();
        foreach ($parameters->propertiesToSet() as $property) {
            $builder->addPropetyToSet($property);
        }
        foreach ($parameters->propertiesToRemove() as $property) {
            $builder->addPropetyToRemove($property);
        }
        return $builder->build();
    }
}
