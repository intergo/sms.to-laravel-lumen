<?php


namespace Intergo\SmsTo\Facades;


use Illuminate\Support\Facades\Facade;
use Intergo\SmsTo\Credentials\ICredential;

/**
 * @package Intergo\SmsTo\SmsToAuth
 * Class SmsToAuth
 *
 * @method static string getType()
 * @method static ICredential verify()
 * @method static string getToken()
 * @method static array getAuthHeader()
 * @method static array refreshToken()
 * @method static array getExpireTTL()
 * @method static array getBalance()
 */
class SmsToAuth extends Facade
{
    /**
     * Return facade accessor
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'smsto-auth';
    }
}