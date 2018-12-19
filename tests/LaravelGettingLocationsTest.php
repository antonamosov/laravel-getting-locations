<?php

use Tests\TestCase;

class LaravelGettingLocationsTest extends TestCase
{
    public function testGetLocationsResultHasDataAttribute()
    {
        $result = app()->make('laravelgettinglocations')->getLocations([
            'name' => 'Moscow City'
        ]);

        $this->assertObjectHasAttribute('data', $result);
    }

    public function testGetLocationsResultHasSuccessAttribute()
    {
        $result = app()->make('laravelgettinglocations')->getLocations([
            'address' => 'Keefe Sellers',
        ]);

        $this->assertObjectHasAttribute('success', $result);
    }

    public function testGetLocationsResultIsSuccess()
    {
        $result = app()->make('laravelgettinglocations')->getLocations([
            'address' => 'Lacinia Road San Bernardino',
        ]);

        $this->assertTrue($result->success);
    }

    public function testGetLocationsDataHasLocationsAttribute()
    {
        $result = app()->make('laravelgettinglocations')->getLocations([
            'country' => 'Russia',
            'address' => 'Petrozavodsk, Lenina 2',
        ]);

        $this->assertObjectHasAttribute('locations', $result->data);
    }

    public function testGetLocationsLocationsAttributeIsArray()
    {
        $result = app()->make('laravelgettinglocations')->getLocations([
            'country' => 'Japan',
        ]);

        $this->assertIsArray($result->data->locations);
    }

    public function testGetLocationsLocationHasNameAttribute()
    {
        $result = app()->make('laravelgettinglocations')->getLocations([
            'country' => 'Japan',
        ]);

        $this->assertObjectHasAttribute('name', $result->data->locations[0]);
    }

    public function testGetLocationsLocationHasCoordinatesAttribute()
    {
        $result = app()->make('laravelgettinglocations')->getLocations([
            'country' => 'Japan',
        ]);

        $this->assertObjectHasAttribute('coordinates', $result->data->locations[0]);
    }

    public function testGetLocationsCoordinatesHasLatAttribute()
    {
        $result = app()->make('laravelgettinglocations')->getLocations([
            'country' => 'Japan',
        ]);

        $this->assertObjectHasAttribute('lat', $result->data->locations[0]->coordinates);
    }

    public function testGetLocationsCoordinatesHasLongAttribute()
    {
        $result = app()->make('laravelgettinglocations')->getLocations([
            'country' => 'Japan',
        ]);

        $this->assertObjectHasAttribute('long', $result->data->locations[0]->coordinates);
    }
}