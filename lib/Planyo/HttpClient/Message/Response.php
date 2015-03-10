<?php

namespace Planyo\HttpClient\Message;

use Guzzle\Http\Message\Response as GuzzleResponse;

class Response
{
    private $response;

    public function __construct(GuzzleResponse $response)
    {
        $this->response = $response;
        return $this;
    }

    public function getContent()
    {
        $body    = $this->response->getBody(true);
        $content = json_decode($body, true);

        if (JSON_ERROR_NONE !== json_last_error()) {
            return $body;
        }

        return $content;
    }

    public function getData()
    {
        $content = $this->getContent();

        if (!isset($content['data'])) {
            return;
        }

        return $content['data'];
    }

    public function getCode()
    {
        $content = $this->getContent();

        if (!isset($content['response_code'])) {
            return;
        }

        return $content['response_code'];
    }
}
