<?php

namespace Eventsaaspro\Facades;

use Illuminate\Support\Facades\Facade;

class EventSaaSPro extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'eventsaaspro';
    }
}
