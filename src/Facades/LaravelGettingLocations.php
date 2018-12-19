<?php

namespace Antonamosov\LaravelGettingLocations\Facades;

use Illuminate\Support\Facades\Facade;

class LaravelGettingLocations extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'laravelgettinglocations';
    }
}
