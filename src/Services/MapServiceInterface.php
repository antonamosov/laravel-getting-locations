<?php

namespace Antonamosov\LaravelGettingLocations\Services;


interface MapServiceInterface
{
    public function listLocations();
    public function setAdminDistrict($adminDistrict);
    public function setCountryRegion($countryRegion);
    public function setAddressLine($addressLine);
    public function setPostalCode($postalCode);
}