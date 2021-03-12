<?php

declare(strict_types=1);

namespace Ngmy\WebDav;

use Http\Client\HttpClient;
use Psr\Http\Message\UriInterface;

class Client
{
    /**
     * Options for the WebDAV client.
     *
     * @var Client\Options
     */
    private $options;

    /**
     * The dispatcher of the WebDAV request.
     *
     * @var Request\Dispatcher
     */
    private $dispatcher;

    /**
     * Create a new instance of the WebDAV Client.
     *
     * @param Client\Options $options    Options for the WebDAV client
     * @param HttpClient     $httpClient An instance of any class that implements the PSR-18 HttpClient
     */
    public function __construct(Client\Options $options = null, HttpClient $httpClient = null)
    {
        $this->options = $options ?: (new Client\Options\Builder())->build();
        $this->dispatcher = new Request\Dispatcher(
            $httpClient ?: (new Http\Client\Factory($this->options))->create()
        );
    }

    /**
     * Perform the WebDAV PUT operation.
     *
     * @param string|UriInterface    $url        The full URL or the URL relative to the base URL of the resource
     * @param Request\Parameters\Put $parameters Parameters for the WebDAV PUT operation
     * @return Response An instance of the Response that implements the PSR-7 ResponseInterface
     */
    public function put($url, Request\Parameters\Put $parameters): Response
    {
        $command = Request\Command::createPutCommand($this->options, $url, $parameters);
        $command->execute($this->dispatcher);
        $response = $command->getResult();
        return new Response($response);
    }

    /**
     * Perform the WebDAV GET operation.
     *
     * @param string|UriInterface $url The full URL or the URL relative to the base URL of the resource
     * @return Response An instance of the Response that implements the PSR-7 ResponseInterface
     */
    public function get($url): Response
    {
        $command = Request\Command::createGetCommand($this->options, $url);
        $command->execute($this->dispatcher);
        $response = $command->getResult();
        return new Response($response);
    }

    /**
     * Perform the WebDAV HEAD operation.
     *
     * @param string|UriInterface $url The full URL or the URL relative to the base URL of the resource
     * @return Response An instance of the Response that implements the PSR-7 ResponseInterface
     */
    public function head($url): Response
    {
        $command = Request\Command::createHeadCommand($this->options, $url);
        $command->execute($this->dispatcher);
        $response = $command->getResult();
        return new Response($response);
    }

    /**
     * Perform the WebDAV DELETE operation.
     *
     * @param string|UriInterface $url The full URL or the URL relative to the base URL of the resource
     * @return Response An instance of the Response that implements the PSR-7 ResponseInterface
     */
    public function delete($url): Response
    {
        $command = Request\Command::createDeleteCommand($this->options, $url);
        $command->execute($this->dispatcher);
        $response = $command->getResult();
        return new Response($response);
    }

    /**
     * Perform the WebDAV MKCOL operation.
     *
     * @param string|UriInterface $url The full URL or the URL relative to the base URL of the resource
     * @return Response An instance of the Response that implements the PSR-7 ResponseInterface
     */
    public function mkcol($url): Response
    {
        $command = Request\Command::createMkcolCommand($this->options, $url);
        $command->execute($this->dispatcher);
        $response = $command->getResult();
        return new Response($response);
    }

    /**
     * Perform the WebDAV COPY operation.
     *
     * @param string|UriInterface     $url        The full URL or the URL relative to the base URL of the resource
     * @param Request\Parameters\Copy $parameters Parameters for the WebDAV COPY operation
     * @return Response An instance of the Response that implements the PSR-7 ResponseInterface
     */
    public function copy($url, Request\Parameters\Copy $parameters): Response
    {
        $command = Request\Command::createCopyCommand($this->options, $url, $parameters);
        $command->execute($this->dispatcher);
        $response = $command->getResult();
        return new Response($response);
    }

    /**
     * Perform the WebDAV MOVE operation.
     *
     * @param string|UriInterface     $url        The full URL or the URL relative to the base URL of the resource
     * @param Request\Parameters\Move $parameters Parameters for the WebDAV MOVE operation
     * @return Response An instance of the Response that implements the PSR-7 ResponseInterface
     */
    public function move($url, Request\Parameters\Move $parameters): Response
    {
        $command = Request\Command::createMoveCommand($this->options, $url, $parameters);
        $command->execute($this->dispatcher);
        $response = $command->getResult();
        return new Response($response);
    }

    /**
     * Perform the WebDAV PROPFIND operation.
     *
     * @param string|UriInterface         $url        The full URL or the URL relative to the base URL of the resource
     * @param Request\Parameters\Propfind $parameters Parameters for the WebDAV PROPFIND operation
     * @return Response An instance of the Response that implements the PSR-7 ResponseInterface
     */
    public function propfind($url, Request\Parameters\Propfind $parameters = null): Response
    {
        $parameters = $parameters ?: new Request\Parameters\Propfind();
        $command = Request\Command::createPropfindCommand($this->options, $url, $parameters);
        $command->execute($this->dispatcher);
        $response = $command->getResult();
        return new Response($response);
    }

    /**
     * Perform the WebDAV PROPPATCH operation.
     *
     * @param string|UriInterface          $url        The full URL or the URL relative to the base URL of the resource
     * @param Request\Parameters\Proppatch $parameters Parameters for the WebDAV PROPPATCH operation
     * @return Response An instance of the Response that implements the PSR-7 ResponseInterface
     */
    public function proppatch($url, Request\Parameters\Proppatch $parameters): Response
    {
        $command = Request\Command::createProppatchCommand($this->options, $url, $parameters);
        $command->execute($this->dispatcher);
        $response = $command->getResult();
        return new Response($response);
    }
}
