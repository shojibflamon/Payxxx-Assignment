<?php

namespace Shojibflamon\PayseraAssignment\Client;

class Response
{
    /**
     * @var
     */
    private $httpCode;

    /**
     * @var
     */
    private $response;

    /**
     * @var
     */
    private $error;

    /**
     * @param $httpCode
     * @param $response
     * @param $error
     */
    public function __construct($httpCode, $response, $error)
    {
        $this->httpCode = $httpCode;
        $this->response = $response;
        $this->error = $error;
    }

    /**
     * @return mixed
     */
    public function getHttpCode()
    {
        return $this->httpCode;
    }

    /**
     * @return mixed
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @return mixed
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * @return mixed
     */
    public function getResponseDecode($is_array = false)
    {
        return $is_array ? json_decode($this->response, $is_array) : json_decode($this->response);
    }

}