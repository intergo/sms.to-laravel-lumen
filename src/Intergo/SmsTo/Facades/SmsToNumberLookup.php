<?php


namespace Intergo\SmsTo\Facades;


use Illuminate\Support\Facades\Facade;

/**
 * @method static array estimate(string $phone)
 * @method static array verify(string $phone)
 */
class SmsToNumberLookup extends Facade
{
    /**
     * Return facade accessor
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'smsto-number-lookup';
    }
}