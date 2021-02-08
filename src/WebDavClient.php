<?php

declare(strict_types=1);

namespace Ngmy\L4Dav;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriInterface;

class WebDavClient
{
    /** @var WebDavClientOptions Options for the WebDAV client */
    private $options;

    /**
     * Create a new instance of the WebDAV Client.
     *
     * @param WebDavClientOptions $options Options for the WebDAV client
     */
    public function __construct(WebDavClientOptions $options = null)
    {
        $this->options = $options ?: (new WebDavClientOptionsBuilder())->build();
    }

    /**
     * Download a file from the WebDAV server.
     *
     * @param string|UriInterface $url        A full URL of a resource, or a URL relative to a base URL of a resource
     * @param GetParameters       $parameters Parameters for the WebDAV GET method
     * @return WebDavResponse An instance of any class that implements the PSR-7 ResponseInterface
     */
    public function get($url, GetParameters $parameters = null): WebDavResponse
    {
        $parameters = $parameters ?: (new GetParametersBuilder())->build();
        $command = WebDavCommand::createGetCommand($url, $parameters, $this->options);
        $command->execute();
        return new WebDavResponse($command->getResult());
    }

    /**
     * Upload a file to the WebDAV server.
     *
     * @param string|UriInterface $url        A full URL of a resource, or a URL relative to a base URL of a resource
     * @param PutParameters       $parameters Parameters for the WebDAV PUT method
     * @return WebDavResponse An instance of any class that implements the PSR-7 ResponseInterface
     */
    public function put($url, PutParameters $parameters): WebDavResponse
    {
        $command = WebDavCommand::createPutCommand($url, $parameters, $this->options);
        $command->execute();
        return new WebDavResponse($command->getResult());
    }

    /**
     * Delete an item on the WebDAV server.
     *
     * @param string|UriInterface $url A full URL of a resource, or a URL relative to a base URL of a resource
     * @return WebDavResponse An instance of any class that implements the PSR-7 ResponseInterface
     */
    public function delete($url): WebDavResponse
    {
        $command = WebDavCommand::createDeleteCommand($url, new DeleteParameters(), $this->options);
        $command->execute();
        return new WebDavResponse($command->getResult());
    }

    /**
     * Copy an item on the WebDAV server.
     *
     * @param string|UriInterface $url        A full URL of a resource, or a URL relative to a base URL of a resource
     * @param CopyParameters      $parameters Parameters for the WebDAV COPY method
     * @return WebDavResponse An instance of any class that implements the PSR-7 ResponseInterface
     */
    public function copy($url, CopyParameters $parameters): WebDavResponse
    {
        $command = WebDavCommand::createCopyCommand($url, $parameters, $this->options);
        $command->execute();
        return new WebDavResponse($command->getResult());
    }

    /**
     * Rename an item on the WebDAV server.
     *
     * @param string|UriInterface $url        A full URL of a resource, or a URL relative to a base URL of a resource
     * @param MoveParameters      $parameters Parameters for the WebDAV MOVE method
     * @return WebDavResponse An instance of any class that implements the PSR-7 ResponseInterface
     */
    public function move($url, MoveParameters $parameters): WebDavResponse
    {
        $command = WebDavCommand::createMoveCommand($url, $parameters, $this->options);
        $command->execute();
        return new WebDavResponse($command->getResult());
    }

    /**
     * Make a directory on the WebDAV server.
     *
     * @param string|UriInterface $url A full URL of a resource, or a URL relative to a base URL of a resource
     * @return WebDavResponse An instance of any class that implements the PSR-7 ResponseInterface
     */
    public function mkcol($url): WebDavResponse
    {
        $command = WebDavCommand::createMkcolCommand($url, new MkcolParameters(), $this->options);
        $command->execute();
        return new WebDavResponse($command->getResult());
    }

    /**
     * Check the existence of an item on the WebDAV server.
     *
     * @param string|UriInterface $url A full URL of a resource, or a URL relative to a base URL of a resource
     * @return WebDavResponse An instance of any class that implements the PSR-7 ResponseInterface
     */
    public function head($url): WebDavResponse
    {
        $command = WebDavCommand::createHeadCommand($url, new HeadParameters(), $this->options);
        $command->execute();
        return new WebDavResponse($command->getResult());
    }

    /**
     * List contents of a directory on the WebDAV server.
     *
     * @param string|UriInterface $url        A full URL of a resource, or a URL relative to a base URL of a resource
     * @param PropfindParameters  $parameters Parameters for the WebDAV PROPFIND method
     * @return WebDavResponse An instance of any class that implements the PSR-7 ResponseInterface
     */
    public function propfind($url, PropfindParameters $parameters = null): WebDavResponse
    {
        $parameters = $parameters ?: (new PropfindParametersBuilder())->build();
        $command = WebDavCommand::createPropfindCommand($url, $parameters, $this->options);
        $command->execute();
        return new WebDavResponse($command->getResult());
    }

    /**
     * @param string|UriInterface $url A full URL of a resource, or a URL relative to a base URL of a resource
     * @return WebDavResponse An instance of any class that implements the PSR-7 ResponseInterface
     */
    public function proppatch($url, ProppatchParameters $parameters): WebDavResponse
    {
        $command = WebDavCommand::createProppatchCommand($url, $parameters, $this->options);
        $command->execute();
        return new WebDavResponse($command->getResult());
    }
}
