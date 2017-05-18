<?php

namespace DFZ\Dola\Facades;

use Illuminate\Support\Facades\Facade;

class Dola extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'dola';
    }
}
