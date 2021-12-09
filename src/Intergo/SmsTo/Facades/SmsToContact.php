<?php


namespace Intergo\SmsTo\Facades;


use Illuminate\Support\Facades\Facade;

/**
 * @method static array createList($name, $description, $shareWithTeam = 0)
 * @method static array create(string $phone, $listIds, array $otherData = [])
 * @method static array deleteByPhone($phone, $listIds = [])
 * @method static array optinByPhone($phone, $listIds = [])
 * @method static array optoutByPhone($phone, $listIds = [])
 * @method static array recentOptouts()
 * @method static array getContactListByListID($id, $limit = 100, $page = 1, $orderBy = 'firstname', $direction = 'ASC')
 * @method static array allList($name = '', $page = 1, $direction = 'ASC')
 * @method static array deleteListByID($id)
 */
class SmsToContact extends Facade
{
    /**
     * Return facade accessor
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'smsto-contact';
    }
}