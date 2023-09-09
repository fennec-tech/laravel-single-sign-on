<?php

namespace Fennectech\LaravelSingleSignOn\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Fennectech\LaravelSingleSignOn\LaravelSingleSignOn
 */
class LaravelSingleSignOn extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Fennectech\LaravelSingleSignOn\LaravelSingleSignOn::class;
    }
}
