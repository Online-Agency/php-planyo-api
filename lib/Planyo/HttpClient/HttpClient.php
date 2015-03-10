<?php

namespace Planyo\HttpClient;

use Guzzle\Http\Client as GuzzleClient;
use Guzzle\Http\ClientInterface;
use Guzzle\Http\Message\Request;
use Guzzle\Http\Message\Response;
use Planyo\Exception\ErrorException;

/**
 * Performs requests on the Planyo API. Planyo only supports get and post requests.
 *
 * @author Dagomar Paulides <dagomar@onlineagency.nl>
 */
class HttpClient implements HttpClientInterface
{

    /**
     * @var array
     */
    protected $options = array(
        'base_url'    => 'https://api.planyo.com/rest/',

        'user_agent'  => 'php-planyo-api (https://github.com/Online-Agency/php-planyo-api)',
        'timeout'     => 10,

        'version' => '1'
    );

    /**
     * @param array           $options
     * @param ClientInterface $client
     */
    public function __construct(array $options = array(), ClientInterface $client = null)
    {
        $this->options = array_merge($this->options, $options);
        $client = $client ?: new GuzzleClient($this->options['base_url'], $this->options);
        $this->client  = $client;

    }

    /**
     * {@inheritDoc}
     */
    public function get(array $parameters = array(), array $headers = array())
    {
        return $this->request(null, 'GET', $headers, array('query' => $parameters));
    }

    /**
     * {@inheritDoc}
     */
    public function post($body = null, array $headers = array())
    {

    }

    /**
     * {@inheritDoc}
     */
    public function request($body = null, $httpMethod = 'GET', array $headers = array(), array $options = array())
    {
        $request = $this->createRequest($httpMethod, $body, $headers, $options);

        try {
            $response = $this->client->send($request);
        } catch (\LogicException $e) {
            throw new ErrorException($e->getMessage(), $e->getCode(), $e);
        } catch (TwoFactorAuthenticationRequiredException $e) {
            throw $e;
        } catch (\RuntimeException $e) {
            throw new RuntimeException($e->getMessage(), $e->getCode(), $e);
        }

        $this->lastRequest  = $request;
        $this->lastResponse = $response;

        return $response;
    }

    /**
     * {@inheritDoc}
     */
    public function setOption($name, $value)
    {

    }

    /**
     * {@inheritDoc}
     */
    public function setHeaders(array $headers)
    {

    }

    protected function createRequest($httpMethod, $body = null, array $headers = array(), array $options = array())
    {
        return $this->client->createRequest(
            $httpMethod,
            '',
            array_merge($this->headers, $headers),
            $body,
            $options
        );
    }
}
