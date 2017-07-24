<?php
namespace Ace\Store;

use Ace\Store\UnavailableException;

/**
 * @author timrodger
 * Date: 29/03/15
 */
class Unavailable implements StoreInterface
{
    /**
     *
     * @param $role
     */
    public function setRole($role)
    {
        throw new UnavailableException('Store is not available');
    }

    /**
     * @param $role
     */
    public function getRole($role)
    {
        throw new UnavailableException('Store is not available');
    }

    /**
     * @throws UnavailableException
     */
    public function listRoles()
    {
        throw new UnavailableException('Store is not available');
    }

    /**
     * @param $role
     * @throws UnavailableException
     */
    public function deleteRole($role)
    {
        throw new UnavailableException('Store is not available');
    }

    /**
     * @param $role
     */
    public function getRoleMembers($role)
    {
        throw new UnavailableException('Store is not available');
    }

    /**
     * @param $role
     * @param $member
     */
    public function addMemberToRole($role, $member)
    {
        throw new UnavailableException('Store is not available');
    }

    /**
     * @param $role
     * @param $member
     */
    public function memberBelongsToRole($role, $member)
    {
        throw new UnavailableException('Store is not available');
    }

    /**
     * @param $role
     * @param $member
     */
    public function removeMemberFromRole($role, $member)
    {
        throw new UnavailableException('Store is not available');
    }

    public function listMemberRoles($member)
    {
        throw new UnavailableException('Store is not available');
    }
}