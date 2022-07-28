<?php

namespace Shojibflamon\PayseraAssignment\Client;

class CurlClient
{
    private string $url;
    private string $method;
    private array $data;
    private array $header;
    private int $timeout;

    /**
     *  constructor
     */
    public function __construct()
    {
        $this->data = [];
        $this->header = [];
        $this->timeout = 30;
    }

    /**
     * @param int $timeout
     */
    public function setTimeout(int $timeout): CurlClient
    {
        $this->timeout = $timeout;
        return $this;
    }


    /**
     * @param mixed $url
     */
    public function setUrl($url): CurlClient
    {
        $this->url = $url;
        return $this;
    }

    /**
     * @param mixed $method
     */
    public function setMethod($method): CurlClient
    {
        $this->method = $method;
        return $this;
    }

    /**
     * @param array $data
     */
    public function setData(array $data): CurlClient
    {
        $this->data = $data;
        return $this;
    }

    /**
     * @param array $header
     */
    public function setHeader(array $header): CurlClient
    {
        $this->header = $header;
        return $this;
    }

    /**
     * @param mixed Response
     */
    public function callApi(): Response
    {
        $curl = curl_init();

        switch ($this->method) {
            case "POST":
                curl_setopt($curl, CURLOPT_POST, true);

                if ($this->data) {
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $this->data);
                }
                break;
            case "PUT":
                curl_setopt($curl, CURLOPT_PUT, 1);
                break;
            default:
                if ($this->data) {
                    $this->url = sprintf("%s?%s", $this->url, http_build_query($this->data));
                }
        }


        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);

        curl_setopt($curl, CURLOPT_URL, $this->url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, $this->timeout);
        curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $this->header);

        $result = curl_exec($curl);

        $error = curl_error($curl);
        $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        curl_close($curl);

        return new Response($http_code, $result, $error);
    }
}
