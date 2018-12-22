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
Antonamosov\LaravelGettingLocations\Facades\LaravelGettingLocations::class,
```

You can also publish the config file to change implementations

``` bash
php artisan vendor:publish --provider="Antonamosov\LaravelGettingLocations\LaravelGettingLocationsServiceProvider" --tag="config"
```

## Testing

``` bash
./vendor/bin/phpunit vendor/antonamosov/laravel-getting-locations/tests/LaravelGettingLocationsTest.php
```