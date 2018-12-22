<?php

namespace Antonamosov\LaravelGettingLocations\Services;

use Antonamosov\LaravelGettingLocations\CurlClient;
use Antonamosov\LaravelGettingLocations\Exceptions\ConnectionParameterNotDefinedException;
use Antonamosov\LaravelGettingLocations\Exceptions\DecodeJsonResultException;
use Antonamosov\LaravelGettingLocations\Exceptions\CurlClientException;
use Antonamosov\LaravelGettingLocations\Exceptions\FailedJsonFormatException;
use Antonamosov\LaravelGettingLocations\Exceptions\InternalHostConnectionException;
use Psr\Http\Message\ResponseInterface;

class BingMapsService implements MapServiceInterface
{
    /**
     * @var string
     */
    private $adminDistrict;

    /**
     * @var string
     */
    private $countryRegion;

    /**
     * @var string
     */
    private $addressLine;

    /**
     * @var string
     */
    private $postalCode;

    /**
     * @var string
     */
    private $apiKey;

    /**
     * @var string
     */
    private $baseUri;

    /**
     * @var mixed | ResponseInterface
     */
    private $response;

    /**
     * @var CurlClient 
     */
    private $curlClient;

    private $allowedParameters = [
        'adminDistrict',
        'countryRegion',
        'addressLine',
        'postalCode',
    ];

    /**
     * BingMapsService constructor.
     * @param CurlClient $curlClient
     */
    public function __construct(CurlClient $curlClient)
    {
        $this->curlClient = $curlClient;
        $this->apiKey = config('getting-locations.api_key');
        $this->baseUri = config('getting-locations.base_uri');
    }

    /**
     * @return object
     */
    public function listLocations()
    {
        try {
            $this->response = $this->curlClient->get([
                'base_uri' => $this->baseUri,
                'uri' => '/REST/v1/locations',
                'parameters' => $this->getParameters(),
            ]);

            $this->checkResponse();

            return $this->getSuccessResult();
        }
        catch (CurlClientException $e) {
            $this->logError($e);
            return $this->getErrorResult('Curl client Error.');
        }
        catch (FailedJsonFormatException $e) {
            $this->logError($e);
            return $this->getErrorResult('Failed JSON Format.');
        }
        catch (DecodeJsonResultException $e) {
            $this->logError($e);
            return $this->getErrorResult('Error while decode json result.');
        }
        catch (ConnectionParameterNotDefinedException $e) {
            $this->logError($e);
            return $this->getErrorResult($e->getMessage());
        }
        catch (\Exception $e) {
            $this->logError($e);
            return $this->getErrorResult('BingMapsService Error.');
        }
    }

    /**
     * @param $adminDistrict
     */
    public function setAdminDistrict($adminDistrict)
    {
        $this->adminDistrict = $adminDistrict;
    }

    /**
     * @param $countryRegion
     */
    public function setCountryRegion($countryRegion)
    {
        $this->countryRegion = $countryRegion;
    }

    /**
     * @param $addressLine
     */
    public function setAddressLine($addressLine)
    {
        $this->addressLine = $addressLine;
    }

    /**
     * @param $postalCode
     */
    public function setPostalCode($postalCode)
    {
        $this->postalCode = $postalCode;
    }

    /**
     * @param $message
     * @return object
     */
    private function getErrorResult($message)
    {
        return $this->getResult((object) [
            'message' => $message,
            'code' => $this->response ? $this->response->getStatusCode() : 500,
        ], false);
    }

    /**
     * @return object
     * @throws FailedJsonFormatException
     * @throws DecodeJsonResultException
     * @throws FailedJsonFormatException
     */
    private function getSuccessResult()
    {
        return $this->getResult($this->getFormattedLocations(), true);
    }

    /**
     * @return object
     * @throws FailedJsonFormatException
     * @throws DecodeJsonResultException
     */
    private function getFormattedLocations()
    {
        try {
            $body = json_decode($this->response->getBody()->getContents());   
        }
        catch (\Exception $e) {
            throw new DecodeJsonResultException($e->getMessage());
        }
        $data = (object) [
            'locations' => [],
        ];

        try {
            foreach ($body->resourceSets as $resourceSet) {
                foreach ($resourceSet->resources as $resource) {
                    $data->locations[] = (object) [
                        'name' => $resource->name,
                        'coordinates' => (object) [
                            'lat' => $resource->point->coordinates[0],
                            'long' => $resource->point->coordinates[1],
                        ],
                    ];
                }
            }
        }
        catch (\Exception $e) {
            throw new FailedJsonFormatException($e->getMessage());
        }

        return $data;
    }

    /**
     * @param $data
     * @param bool $success
     * @return object
     */
    private function getResult($data, $success)
    {
        return (object) [
            'data' => $data,
            'success' => $success
        ];
    }

    /**
     * @throws InternalHostConnectionException
     */
    private function checkResponse()
    {
        if ($this->response->getStatusCode() !== 200) {
            throw new InternalHostConnectionException($this->getErrorMessage());
        }
    }

    /**
     * @return string
     */
    private function getErrorMessage()
    {
        $body = json_decode($this->response->getBody()->getContents());
        
        return isset($body->statusDescription) ? $body->statusDescription : 'The status of response not OK.';
    }

    /**
     * @return array
     */
    private function getParameters()
    {
        $parameters = [
            'key' => $this->apiKey,
        ];

        foreach ($this->allowedParameters as $parameter) {
            if ($this->{$parameter}) {
                $parameters = array_merge($parameters, [
                    $parameter => $this->{$parameter},
                ]);
            }
        }
        
        return $parameters;
    }

    /**
     * @param \Exception $e
     */
    private function logError(\Exception $e)
    {
        \Log::error($e);
    }
}