<?php

namespace Shopello\API;

use Curl\Curl;
use Exception;

class ApiFactory
{
    /**
     * @var string Api username
     */
    protected $username;

    /**
     * @var string Api password
     */
    protected $password;

    /**
     * @var array Api keys for diffrent countrys
     */
    protected $keys;

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
     * @param string $username Api username
     *
     * @param string $pasword Api password
     *
     * @param array $keys Array of api keys mapped to country code
     */
    public function __construct(Curl $curl, $username, $password, array $keys)
    {
        $this->curl = $curl;
        $this->username = $username;
        $this->password = $password;
        $this->keys = $keys;
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
        if ($this->keys[$country]) {
            $instance = $this->instances['v1'][$country] = new ApiClient($this->curl);
            $instance->setApiEndpoint($this->endpoints[$country] . '1/');
            $instance->setApiKey($this->keys[$country]);
            return $this->instances['v1'][$country] = $instance;
        }
        throw Exception('Invalid country. No credentials found');
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
        if ($this->keys[$country]) {
            $instance = $this->instances['v3'][$country] = new Api3Client($this->curl);
            $instance->setApiCredentials($this->username, $this->password);
            $instance->setApiEndpoint($this->endpoints[$country] . 'v3/');
            $instance->setApiKey($this->keys[$country]);
            return $this->instances['v3'][$country] = $instance;
        }
        throw Exception('Invalid country. No credentials found');
    }
}
