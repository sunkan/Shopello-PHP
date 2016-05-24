<?php

namespace Shopello\API;

use Curl\Curl;
use Exception;

class ApiFactory
{
    /**
     * @var array Api credentials for diffrent countrys
     */
    protected $credentials;

    /**
     * @var Curl
     */
    protected $curl;

    /**
     * @var array of endpoints
     */
    protected $endpoints = [
        'se' => 'https://se.shopelloapi.com/',
        'no' => 'https://no.shopelloapi.com/',
        'dk' => 'https://dk.shopelloapi.com/',
        'fi' => 'https://fi.shopelloapi.com/',
    ];

    /**
     * @var array of instances
     */
    protected $instances = [
        'v1' => [],
        'v3' => [],
    ];

    /**
     * Constructor
     *
     * @param Curl $curl Curl instance
     * @param array $credentials Array of api credentials mapped to country code
     */
    public function __construct(Curl $curl, array $credentials)
    {
        $this->curl = $curl;
        $this->credentials = $credentials;
    }

    /**
     * @param string $country Which endpoint to user
     * @throws Exception Throws execption on missing credentials
     * @return ApiClient
     */
    public function newApiV1($country)
    {
        if (isset($this->instances['v1'][$country])) {
            return $this->instances['v1'][$country];
        }
        if ($this->credentials[$country]) {
            $credentials = $this->credentials[$country];
            $instance = $this->instances['v1'][$country] = new ApiClient($this->curl);
            $instance->setApiEndpoint($this->endpoints[$country] . '1/');
            $instance->setApiKey($credentials['key']);
            return $this->instances['v1'][$country] = $instance;
        }
        throw new Exception('Invalid country. No credentials found');
    }

    /**
     * @param string $country Which endpoint to user
     * @throws Exception Throws execption on missing credentials
     * @return Api3Client
     */
    public function newApiV3($country)
    {
        if (isset($this->instances['v3'][$country])) {
            return $this->instances['v3'][$country];
        }
        if ($this->credentials[$country]) {
            $credentials = $this->credentials[$country];
            $instance = $this->instances['v3'][$country] = new Api3Client($this->curl);
            $instance->setApiCredentials($credentials['username'], $credentials['password']);
            $instance->setApiEndpoint($this->endpoints[$country] . 'v3/');
            $instance->setApiKey($credentials['key']);
            return $this->instances['v3'][$country] = $instance;
        }
        throw new Exception('Invalid country. No credentials found');
    }
}
