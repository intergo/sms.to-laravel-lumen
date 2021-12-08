<?php


namespace Intergo\SmsTo\Facades;


use Illuminate\Support\Facades\Facade;

/**
 * @method static array create(string $name, string $url)
 * @method static array all($limit = 20, $page = 1, $sort = 'created_at')
 * @method static array getByID($id)
 * @method static array deleteByID($id)
 */
class SmsToShortlink extends Facade
{
    /**
     * Return facade accessor
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'smsto-shortlink';
    }
}