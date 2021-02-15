<?php namespace Econtract\Steam\Facades;


use Illuminate\Support\Facades\Facade;

class Steam extends Facade {

    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'Steam';
    }

}
