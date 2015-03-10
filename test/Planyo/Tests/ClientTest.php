<?php

namespace Planyo\Tests;

use Planyo\Client;

class ClientTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @test
     */
    public function shouldNotHaveToPassHttpClientToConstructor()
    {
        $client = new Client();

        $this->assertInstanceOf('Planyo\HttpClient\HttpClient', $client->getHttpClient());
    }

    /**
     * @test
     */
    public function shouldPassHttpClientInterfaceToConstructor()
    {
        $client = new Client($this->getHttpClientMock());

        $this->assertInstanceOf('Planyo\HttpClient\HttpClientInterface', $client->getHttpClient());
    }

    /**
     * @test
     */
    public function shouldSetHttpClientInterfaceToConstructor()
    {
        $client = new Client();

        $client->setHttpClient($this->getHttpClientMock());

        $this->assertInstanceOf('Planyo\HttpClient\HttpClientInterface', $client->getHttpClient());
    }

    /**
     * @test
     */
    public function shouldReturnApiKey()
    {
        $apiKey = 'test_api_key';

        $client = new Client();

        $client->setApiKey($apiKey);

        $this->assertEquals($apiKey, $client->getApiKey());
    }

    /**
     * @test
     * @dataProvider getApiClassesProvider
     */
    public function shouldGetApiInstance($apiName, $class)
    {
        $client = new Client();

        $this->assertInstanceOf($class, $client->api($apiName));
    }

    /**
     * @test
     * @dataProvider getApiClassesProvider
     */
    public function shouldGetMagicApiInstance($apiName, $class)
    {
        $client = new Client();

        $this->assertInstanceOf($class, $client->$apiName());
    }

    /**
     * @test
     * @expectedException InvalidArgumentException
     */
    public function shouldNotGetApiInstance()
    {
        $client = new Client();
        $client->api('do_not_exist');
    }

    /**
     * @test
     * @expectedException BadMethodCallException
     */
    public function shouldNotGetMagicApiInstance()
    {
        $client = new Client();
        $client->doNotExist();
    }

    /**
     * Provide api classes
     */
    public function getApiClassesProvider()
    {
        return array(
            array('get_resource_info', 'Planyo\Api\PlanyoApi'),
            array('list_reservations', 'Planyo\Api\PlanyoApi'),
            array('list_resources', 'Planyo\Api\PlanyoApi'),
        );
    }

    /**
     * return mock for HttpClient
     */
    public function getHttpClientMock(array $methods = array())
    {
        $methods = array_merge(
            array('get', 'post', 'request', 'setOption', 'setHeaders'),
            $methods
        );

        return $this->getMock('Planyo\HttpClient\HttpClientInterface', $methods);
    }
}
