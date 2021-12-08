<?php


namespace Intergo\SmsTo\Facades;


use Illuminate\Support\Facades\Facade;

/**
 * @method static array allMembers()
 * @method static array allInvitations()
 * @method static array generateMember()
 * @method static array inviteMemberByEmail(string $email)
 * @method static array disableMemberByID($id)
 * @method static array enableMemberByID($id)
 * @method static array creditMemberByID($id, $amount)
 * @method static array debitMemberByID($id, $amount)
 * @method static array deleteMemberByID($id, $amount)
 * @method static array deleteInviteByID($id)
 */
class SmsToTeam extends Facade
{
    /**
     * Return facade accessor
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'smsto-team';
    }
}