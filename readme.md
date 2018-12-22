# Laravel Getting Locations

An laravel package for a getting of locations using custom service.

## Installation

Require this package with composer using the following command:

``` bash
$ composer require "antonamosov/laravel-getting-locations @dev"
```

After updating composer, add the service provider to the providers array in config/app.php

``` bash
Antonamosov\LaravelGettingLocations\LaravelGettingLocationsServiceProvider::class,
```

And add the alias to aliases array in config/app.php

``` bash
'GettingLocations' => Antonamosov\LaravelGettingLocations\Facades\LaravelGettingLocations::class,
```

Publish the config file to change implementations, select service and API key:

``` bash
$ php artisan vendor:publish --provider="Antonamosov\LaravelGettingLocations\LaravelGettingLocationsServiceProvider" --tag="config"
```

## Usage

``` bash
use LaravelGettingLocations;

$response = LaravelGettingLocations::getLocations([
        'name' => 'Moscow City',
        'address' => 'Lenina',
        'postalCode' => '185000',
        'country' => 'Russia',
    ]);
        
if ($response->success) {
        foreach ($response->data->locations as $location) {
            echo $location->name . "\n";
            echo $location->coordinates->lat . "\n";
            echo $location->coordinates->long . "\n\n";
        }
    }        
```

## Testing

``` bash
./vendor/bin/phpunit vendor/antonamosov/laravel-getting-locations/tests/LaravelGettingLocationsTest.php
```