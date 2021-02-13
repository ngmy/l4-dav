<?php

declare(strict_types=1);

namespace Ngmy\PhpWebDav;

use Http\Client\HttpClient;
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
     * The dispatcher of the WebDAV request.
     *
     * @var WebDavRequestDispatcher
     */
    private $dispatcher;

    /**
     * Create a new instance of the WebDAV Client.
     *
     * @param WebDavClientOptions $options    Options for the WebDAV client
     * @param HttpClient          $httpClient An instance of any class that implements the PSR-18 HttpClient
     */
    public function __construct(WebDavClientOptions $options = null, HttpClient $httpClient = null)
    {
        $this->options = $options ?: (new WebDavClientOptionsBuilder())->build();
        $this->dispatcher = new WebDavRequestDispatcher(
            $httpClient ?: (new HttpClientFactory($this->options))->create()
        );
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
        $command = WebDavRequestCommand::createPutCommand($this->options, $url, $parameters);
        $command->execute($this->dispatcher);
        $response = $command->getResult();
        return new WebDavResponse($response);
    }

    /**
     * Perform the WebDAV GET operation.
     *
     * @param string|UriInterface $url The full URL or the URL relative to the base URL of the resource
     * @return WebDavResponse An instance of the WebDavResponse that implements the PSR-7 ResponseInterface
     */
    public function get($url): WebDavResponse
    {
        $command = WebDavRequestCommand::createGetCommand($this->options, $url);
        $command->execute($this->dispatcher);
        $response = $command->getResult();
        return new WebDavResponse($response);
    }

    /**
     * Perform the WebDAV HEAD operation.
     *
     * @param string|UriInterface $url The full URL or the URL relative to the base URL of the resource
     * @return WebDavResponse An instance of the WebDavResponse that implements the PSR-7 ResponseInterface
     */
    public function head($url): WebDavResponse
    {
        $command = WebDavRequestCommand::createHeadCommand($this->options, $url);
        $command->execute($this->dispatcher);
        $response = $command->getResult();
        return new WebDavResponse($response);
    }

    /**
     * Perform the WebDAV DELETE operation.
     *
     * @param string|UriInterface $url The full URL or the URL relative to the base URL of the resource
     * @return WebDavResponse An instance of the WebDavResponse that implements the PSR-7 ResponseInterface
     */
    public function delete($url): WebDavResponse
    {
        $command = WebDavRequestCommand::createDeleteCommand($this->options, $url);
        $command->execute($this->dispatcher);
        $response = $command->getResult();
        return new WebDavResponse($response);
    }

    /**
     * Perform the WebDAV MKCOL operation.
     *
     * @param string|UriInterface $url The full URL or the URL relative to the base URL of the resource
     * @return WebDavResponse An instance of the WebDavResponse that implements the PSR-7 ResponseInterface
     */
    public function mkcol($url): WebDavResponse
    {
        $command = WebDavRequestCommand::createMkcolCommand($this->options, $url);
        $command->execute($this->dispatcher);
        $response = $command->getResult();
        return new WebDavResponse($response);
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
        $command = WebDavRequestCommand::createCopyCommand($this->options, $url, $parameters);
        $command->execute($this->dispatcher);
        $response = $command->getResult();
        return new WebDavResponse($response);
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
        $command = WebDavRequestCommand::createMoveCommand($this->options, $url, $parameters);
        $command->execute($this->dispatcher);
        $response = $command->getResult();
        return new WebDavResponse($response);
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
        $parameters = $parameters ?: new PropfindParameters();
        $command = WebDavRequestCommand::createPropfindCommand($this->options, $url, $parameters);
        $command->execute($this->dispatcher);
        $response = $command->getResult();
        return new WebDavResponse($response);
    }

    /**
     * Perform the WebDAV PROPPATCH operation.
     *
     * @param string|UriInterface $url The full URL or the URL relative to the base URL of the resource
     * @return WebDavResponse An instance of the WebDavResponse that implements the PSR-7 ResponseInterface
     */
    public function proppatch($url, ProppatchParameters $parameters): WebDavResponse
    {
        $command = WebDavRequestCommand::createProppatchCommand($this->options, $url, $parameters);
        $command->execute($this->dispatcher);
        $response = $command->getResult();
        return new WebDavResponse($response);
    }
}
