<?php

namespace Antonamosov\LaravelGettingLocations;


use Antonamosov\LaravelGettingLocations\Exceptions\ConnectionParameterNotDefinedException;
use Antonamosov\LaravelGettingLocations\Exceptions\CurlClientException;
use Antonamosov\LaravelGettingLocations\Exceptions\UnsupportedRequestMethodException;
use GuzzleHttp\Client;

class CurlClient
{
    /**
     * @var array
     */
    private $connectionParameters;

    /**
     * @var string
     */
    private $method;

    /**
     * @var Client
     */
    private $client;
    
    const METHOD_GET = 'GET';
    const METHOD_POST = 'POT';
    const METHOD_PUT = 'PUT';
    const METOD_DELETE = 'DELETE';
    
    const TIMEOUT = 5.0;

    /**
     * @var array 
     */
    private $supportedMethods = [
        self::METHOD_GET,
        self::METHOD_POST,
    ];

    /**
     * @var array 
     */
    private $requiredConnectionParameters = [
        'base_uri',
        'uri',
        'parameters',
    ];

    /**
     * @param array $connectionParameters
     * @return mixed
     * @throws ConnectionParameterNotDefinedException
     * @throws CurlClientException
     * @throws UnsupportedRequestMethodException
     */
    public function get(array $connectionParameters)
    {
        $this->setConnectionParameters($connectionParameters);
        $this->checkConnectionParameters();
        $this->setMethod(self::METHOD_GET);
        $this->setClient();

        return $this->connect();
    }

    /**
     * @param array $connectionParameters
     * @return mixed
     * @throws ConnectionParameterNotDefinedException
     * @throws CurlClientException
     * @throws UnsupportedRequestMethodException
     */
    public function post(array $connectionParameters)
    {
        $this->setConnectionParameters($connectionParameters);
        $this->checkConnectionParameters();
        $this->setMethod(self::METHOD_POST);
        $this->setClient();

        return $this->connect();
    }

    /**
     * @return mixed
     * @throws CurlClientException
     */
    private function connect()
    {
        try {
            if ($this->method === self::METHOD_GET) {
                return $this->client->request(self::METHOD_GET, $this->getUri());
            }
            elseif ($this->method === self::METHOD_POST) {
                return $this->client->request(self::METHOD_POST, $this->getUri(), $this->getParameters());
            }
        }
        catch (\Exception $e) {
            throw new CurlClientException($e->getMessage());
        }
    }

    /**
     *
     */
    private function setClient()
    {
        $this->client = new Client([
            'base_uri' => $this->getBaseUri(),
            'timeout'  => self::TIMEOUT,
        ]);
    }

    /**
     * @return mixed
     */
    private function getBaseUri()
    {
        return $this->connectionParameters['base_uri'];
    }

    /**
     * @return mixed|string
     */
    private function getUri()
    {
        if ($this->method === self::METHOD_GET) {
            return $this->connectionParameters['uri'] . '?' . http_build_query($this->getParameters());
        }
        elseif ($this->method === self::METHOD_POST) {
            return $this->connectionParameters['uri'];
        }
    }

    /**
     * @return mixed
     */
    private function getParameters()
    {
        return $this->connectionParameters['parameters'];
    }

    /**
     * @param $method
     * @throws UnsupportedRequestMethodException
     */
    private function setMethod($method)
    {
        if (! isset( array_flip($this->supportedMethods)[$method] )) {
            throw new UnsupportedRequestMethodException('Unsupported request method: ' . $method);
        }
        $this->method = $method;
    }

    /**
     * @param $connectionParameters
     */
    private function setConnectionParameters($connectionParameters)
    {
        $this->connectionParameters = $connectionParameters;
    }

    /**
     * @throws ConnectionParameterNotDefinedException
     */
    private function checkConnectionParameters()
    {
        foreach ($this->connectionParameters as $parameterName => $parameter) {
            if (! isset(array_flip($this->requiredConnectionParameters)[$parameterName])) {
                throw new ConnectionParameterNotDefinedException($parameterName . ' connection parameter must be defined.');
            }
        }
    }
}