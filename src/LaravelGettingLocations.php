<?php

namespace Antonamosov\LaravelGettingLocations;

use Antonamosov\LaravelGettingLocations\Exceptions\AttributesFiledsNotAllowedException;
use Antonamosov\LaravelGettingLocations\Exceptions\MapPointParametersNotDefinedException;
use Antonamosov\LaravelGettingLocations\Services\MapServiceInterface;

class LaravelGettingLocations
{
    /**
     * @var array
     */
    private $mapPointParameters;

    /**
     * @var MapsInterface
     */
    private $mapService;

    /**
     * @var object
     */
    private $locations;

    /**
     * @var array 
     */
    private $allowedAttributesFields = [
        'name',
        'country',
        'address',
        'postalCode',
    ];

    /**
     * LaravelGettingLocations constructor.
     * @param MapServiceInterface $mapService
     */
    public function __construct(MapServiceInterface $mapService)
    {
        $this->mapService = $mapService;
    }

    /**
     * @param array $mapPointParameters
     * @return object
     * @throws MapPointParametersNotDefinedException
     */
    public function getLocations(array $mapPointParameters)
    {
        $this->setMapPointParameters($mapPointParameters);
        $this->checkMapPointParameters();

        $this->mapService->setAdminDistrict($this->getName());
        $this->mapService->setCountryRegion($this->getCountry());
        $this->mapService->setAddressLine($this->getAddress());
        $this->mapService->setPostalCode($this->getPostalCode());

        $this->setLocations($this->mapService->listLocations());

        return $this->getFormattedLocations();
    }

    /**
     * @return object
     */
    private function getFormattedLocations()
    {
        return $this->locations;
    }

    /**
     * @return mixed|null
     */
    private function getName()
    {
        return $this->getMapParameter('name');
    }

    /**
     * @return mixed|null
     */
    private function getCountry()
    {
        return $this->getMapParameter('country');
    }

    /**
     * @return mixed|null
     */
    private function getAddress()
    {
        return $this->getMapParameter('address');
    }

    /**
     * @return mixed|null
     */
    private function getPostalCode()
    {
        return $this->getMapParameter('postalCode');
    }

    /**
     * @param $name
     * @return mixed|null
     */
    private function getMapParameter($name)
    {
        return isset($this->mapPointParameters[$name]) ? $this->mapPointParameters[$name] : null; 
    }

    /**
     * @param $locations
     */
    private function setLocations($locations)
    {
        $this->locations = $locations;
    }

    /**
     * @param array $mapPointParameters
     */
    private function setMapPointParameters(array $mapPointParameters)
    {
        $this->mapPointParameters = $mapPointParameters;
    }

    /**
     * @throws AttributesFiledsNotAllowedException
     * @throws MapPointParametersNotDefinedException
     */
    private function checkMapPointParameters()
    {
        if (! count($this->mapPointParameters)) {
            throw new MapPointParametersNotDefinedException('Map point parameters must be defined.');
        }

        $arrDiff = array_diff_key($this->mapPointParameters, array_flip($this->allowedAttributesFields));

        if (count($arrDiff)) {
            throw new AttributesFiledsNotAllowedException(implode(', ', array_flip($arrDiff)) . " map point parameters not allowed.");
        }
    }
}