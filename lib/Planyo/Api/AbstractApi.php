<?php

namespace Planyo\Api;

use Planyo\Client;
use Planyo\HttpClient\Message\Response;

/**
 * Abstract class for Api classes
 *
 * @author Dagomar Paulides <dagomar@onlineagency.nl>
 */
abstract class AbstractApi
{
    /**
     * The client
     *
     * @var Client
     */
    protected $client;

    /**
     * @param Client $client
     */
    public function __construct(Client $client, $method, array $required = array())
    {
        $this->client = $client;
        $this->method = $method;
        $this->required = $required;
    }

    /**
     * Send a GET request with query parameters.
     *
     * @param string $path              Request path.
     * @param array $parameters         GET parameters.
     * @param array $requestHeaders     Request Headers.
     * @return \Guzzle\Http\EntityBodyInterface|mixed|string
     */
    protected function get(array $parameters = array(), $requestHeaders = array())
    {
        $parameters['api_key'] = $this->authenticate();

        $response = $this->client->getHttpClient()->get($parameters, $requestHeaders);

        $r = new Response($response);

        return $r;
    }

    /**
     * Check required parameters
     * @param  array  $params   Parameters
     * @param  array  $required List of required parameter names
     * @throws MissingArgumentException If required is not found.
     * @return array
     */
    protected function checkRequired(array $params)
    {
        $missing = array();
        foreach ($this->required as $value) {
            if (!isset($params[$value])) {
                $missing[] = $value;
            }
        }
        return $missing;
    }

    protected function authenticate()
    {
        return $this->client->getApiKey();
    }
}
