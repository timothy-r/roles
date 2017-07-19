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
    public function set($role)
    {
        throw new UnavailableException('Store is not available');
    }

    /**
     * @param $role
     */
    public function get($role)
    {
        throw new UnavailableException('Store is not available');
    }

    /**
     * @throws UnavailableException
     */
    public function listAll()
    {
        throw new UnavailableException('Store is not available');
    }

    /**
     * @param $role
     * @throws UnavailableException
     */
    public function delete($role)
    {
        throw new UnavailableException('Store is not available');
    }


    /**
     * @param $role
     * @param $member
     */
    public function addMember($role, $member)
    {
        throw new UnavailableException('Store is not available');
    }

    /**
     * @param $role
     * @param $member
     */
    public function removeMember($role, $member)
    {
        throw new UnavailableException('Store is not available');
    }
}