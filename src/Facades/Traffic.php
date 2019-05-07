<?php

namespace AdrianBav\Traffic\Facades;

use Illuminate\Support\Facades\Facade;

class Traffic extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'traffic';
    }
}
