<?php

namespace Planyo;

use Planyo\Exception\BadMethodCallException;
use Planyo\Exception\InvalidArgumentException;
use Planyo\HttpClient\HttpClientInterface;
use Planyo\HttpClient\HttpClient;

/**
 * Planyo API
 *
 * @author  Dagomar Paulides <dagomar@onlineagency.nl>
 *
 */

class Client
{

    /**
     * Api key
     */
    private $apiKey;

    /**
     * @var array
     */
    private $options = array(
        'base_url'    => 'https://api.planyo.com/rest/',

        'user_agent'  => 'php-planyo-api (https://github.com/Online-Agency/php-planyo-api)',
        'timeout'     => 10,

        'version' => '1',
    );

    /**
     * The Buzz instance used to communicate with Planyo
     *
     * @var HttpClient
     */
    private $httpClient;

    /**
     * Instantiate a new Planyo client
     *
     * @param null|HttpClientInterface $httpClient Planyo http client
     */
    public function __construct(HttpClientInterface $httpClient = null)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * @param string $name
     *
     * @return ApiInterface
     *
     * @throws InvalidArgumentException
     */
    public function api($name)
    {
        switch ($name) {

            case 'get_resource_info':
                $api = new Api\PlanyoApi($this, 'get_resource_info', array('resource_id'));
                // $api = new Api\getResourceInfo($this);
                break;

            case 'list_reservations':
                $api = new Api\PlanyoApi($this,'list_reservations',array("start_time", "end_time"));
                break;

            case 'list_resources':
                $api = new Api\PlanyoApi($this, 'list_resources');
                break;

             default:
                throw new InvalidArgumentException(sprintf('Undefined api instance called: "%s"', $name));
        }

        return $api;
    }

    /**
     * @return HttpClient
     */
    public function getHttpClient()
    {
        if (null === $this->httpClient) {
            $this->httpClient = new HttpClient($this->options);
        }

        return $this->httpClient;
    }

    /**
     * @param HttpClientInterface $httpClient
     */
    public function setHttpClient(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * Set the API key.
     * @param string $apiKey API Key
     */
    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    /**
     * Autenticate the user. Currently just returns the api key.
     * @return string Api key
     */
    public function getApiKey()
    {
        return $this->apiKey;
    }

    /**
     * @param string $name
     *
     * @return ApiInterface
     *
     * @throws InvalidArgumentException
     */
    public function __call($name, $args)
    {
        try {
            return $this->api($name);
        } catch (InvalidArgumentException $e) {
            throw new BadMethodCallException(sprintf('Undefined method called: "%s"', $name));
        }
    }
}
