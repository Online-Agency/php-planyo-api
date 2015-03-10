<?php

namespace Planyo\HttpClient;

use Planyo\Exception\InvalidArgumentException;
use Guzzle\Http\Message\Response;

interface HttpClientInterface
{
    /**
     * Send a GET request
     *
     * @param string $path       Request path
     * @param array  $parameters GET Parameters
     * @param array  $headers    Reconfigure the request headers for this call only
     *
     * @return Response
     */
    public function get(array $parameters = array(), array $headers = array());

    /**
     * Send a POST request
     *
     * @param string $path       Request path
     * @param mixed  $body       Request body
     * @param array  $headers    Reconfigure the request headers for this call only
     *
     * @return Response
     */
    public function post($body = null, array $headers = array());

    /**
     * Send a request to the server, receive a response,
     * decode the response and returns an associative array
     *
     * @param string $path       Request path
     * @param mixed  $body       Request body
     * @param string $httpMethod HTTP method to use
     * @param array  $headers    Request headers
     *
     * @return Response
     */
    public function request($body, $httpMethod = 'GET', array $headers = array());

    /**
     * Change an option value.
     *
     * @param string $name  The option name
     * @param mixed  $value The value
     *
     * @throws InvalidArgumentException
     */
    public function setOption($name, $value);

    /**
     * Set HTTP headers
     *
     * @param array $headers
     */
    public function setHeaders(array $headers);

}
