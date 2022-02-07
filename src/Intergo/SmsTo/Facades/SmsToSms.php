<?php


namespace Intergo\SmsTo\Facades;


use Illuminate\Support\Facades\Facade;
use Intergo\SmsTo\Module\Sms\Message\IMessage;

/**
 * @method static array estimate(IMessage $message)
 * @method static array send(IMessage $message)
 * @method static array getCampaigns()
 * @method static array getLastCampaign()
 * @method static array getCampaignByID(string $id)
 * @method static array getMessages()
 * @method static array getLastMessage()
 * @method static array getMessageByID(string $id)
 * @method static array setType(string $type)
 * @method static array isOptedOut(string $phone, string $defaultPrefix = null)
 */
class SmsToSms extends Facade
{
    /**
     * Return facade accessor
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'smsto-sms';
    }
}