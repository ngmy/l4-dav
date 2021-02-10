<?php

declare(strict_types=1);

namespace Ngmy\PhpWebDav;

use Psr\Http\Message\UriInterface;

class WebDavClient
{
    /**
     * Options for the WebDAV client.
     *
     * @var WebDavClientOptions
     */
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
     * Perform the WebDAV GET operation.
     *
     * @param string|UriInterface $url The full URL or the URL relative to the base URL of the resource
     * @return WebDavResponse An instance of the WebDavResponse that implements the PSR-7 ResponseInterface
     */
    public function get($url): WebDavResponse
    {
        $parameters = new GetParameters();
        $command = WebDavCommand::createGetCommand($url, $parameters, $this->options);
        $command->execute();
        return new WebDavResponse($command->getResult());
    }

    /**
     * Perform the WebDAV PUT operation.
     *
     * @param string|UriInterface $url        The full URL or the URL relative to the base URL of the resource
     * @param PutParameters       $parameters Parameters for the WebDAV PUT operation
     * @return WebDavResponse An instance of the WebDavResponse that implements the PSR-7 ResponseInterface
     */
    public function put($url, PutParameters $parameters): WebDavResponse
    {
        $command = WebDavCommand::createPutCommand($url, $parameters, $this->options);
        $command->execute();
        return new WebDavResponse($command->getResult());
    }

    /**
     * Perform the WebDAV DELETE operation.
     *
     * @param string|UriInterface $url The full URL or the URL relative to the base URL of the resource
     * @return WebDavResponse An instance of the WebDavResponse that implements the PSR-7 ResponseInterface
     */
    public function delete($url): WebDavResponse
    {
        $command = WebDavCommand::createDeleteCommand($url, new DeleteParameters(), $this->options);
        $command->execute();
        return new WebDavResponse($command->getResult());
    }

    /**
     * Perform the WebDAV COPY operation.
     *
     * @param string|UriInterface $url        The full URL or the URL relative to the base URL of the resource
     * @param CopyParameters      $parameters Parameters for the WebDAV COPY operation
     * @return WebDavResponse An instance of the WebDavResponse that implements the PSR-7 ResponseInterface
     */
    public function copy($url, CopyParameters $parameters): WebDavResponse
    {
        $command = WebDavCommand::createCopyCommand($url, $parameters, $this->options);
        $command->execute();
        return new WebDavResponse($command->getResult());
    }

    /**
     * Perform the WebDAV MOVE operation.
     *
     * @param string|UriInterface $url        The full URL or the URL relative to the base URL of the resource
     * @param MoveParameters      $parameters Parameters for the WebDAV MOVE operation
     * @return WebDavResponse An instance of the WebDavResponse that implements the PSR-7 ResponseInterface
     */
    public function move($url, MoveParameters $parameters): WebDavResponse
    {
        $command = WebDavCommand::createMoveCommand($url, $parameters, $this->options);
        $command->execute();
        return new WebDavResponse($command->getResult());
    }

    /**
     * Perform the WebDAV MKCOL operation.
     *
     * @param string|UriInterface $url The full URL or the URL relative to the base URL of the resource
     * @return WebDavResponse An instance of the WebDavResponse that implements the PSR-7 ResponseInterface
     */
    public function mkcol($url): WebDavResponse
    {
        $command = WebDavCommand::createMkcolCommand($url, new MkcolParameters(), $this->options);
        $command->execute();
        return new WebDavResponse($command->getResult());
    }

    /**
     * Perform the WebDAV HEAD operation.
     *
     * @param string|UriInterface $url The full URL or the URL relative to the base URL of the resource
     * @return WebDavResponse An instance of the WebDavResponse that implements the PSR-7 ResponseInterface
     */
    public function head($url): WebDavResponse
    {
        $command = WebDavCommand::createHeadCommand($url, new HeadParameters(), $this->options);
        $command->execute();
        return new WebDavResponse($command->getResult());
    }

    /**
     * Perform the WebDAV PROPFIND operation.
     *
     * @param string|UriInterface $url        The full URL or the URL relative to the base URL of the resource
     * @param PropfindParameters  $parameters Parameters for the WebDAV PROPFIND operation
     * @return WebDavResponse An instance of the WebDavResponse that implements the PSR-7 ResponseInterface
     */
    public function propfind($url, PropfindParameters $parameters = null): WebDavResponse
    {
        $parameters = $parameters ?: (new PropfindParametersBuilder())->build();
        $command = WebDavCommand::createPropfindCommand($url, $parameters, $this->options);
        $command->execute();
        return new WebDavResponse($command->getResult());
    }

    /**
     * Perform the WebDAV PROPPATCH operation.
     *
     * @param string|UriInterface $url The full URL or the URL relative to the base URL of the resource
     * @return WebDavResponse An instance of the WebDavResponse that implements the PSR-7 ResponseInterface
     */
    public function proppatch($url, ProppatchParameters $parameters): WebDavResponse
    {
        $command = WebDavCommand::createProppatchCommand($url, $parameters, $this->options);
        $command->execute();
        return new WebDavResponse($command->getResult());
    }
}
