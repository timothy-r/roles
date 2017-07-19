<?php
namespace Ace\Store;

use Ace\Store\NotFoundException;

/**
 * @author timrodger
 */
class Memory implements StoreInterface
{
    /**
     * @var array
     */
    private $data = [];

    /**
     *
     * @param $role
     */
    public function set($role)
    {
        if (!isset($this->data[$role])) {
            $this->data[$role] = [];
        }
    }

    /**
     * @param $role
     */
    public function get($role)
    {
        if (isset($this->data[$role])) {
            return $role;
        } else {
            throw new NotFoundException("Role '$role' not found");
        }
    }

    /**
     * @return array
     */
    public function listAll()
    {
        return array_keys($this->data);
    }

    /**
     * @param $role
     */
    public function delete($role)
    {
        unset($this->data[$role]);
    }

    /**
     * @param $role
     * @param $member
     */
    public function addMember($role, $member)
    {
        // ensure role exists
        $this->set($role);

        // make delete easier by storing the member as the key & value
        $this->data[$role] [$member]= $member;
    }

    /**
     * @param $role
     * @param $member
     * @return boolean
     */
    public function memberBelongsToRole($role, $member)
    {
        if (isset($this->data[$role])) {
            return in_array($member, $this->data[$role]);
        }
        return false;
    }

    /**
     * @param $role
     * @param $member
     */
    public function removeMember($role, $member)
    {
        if (isset($this->data[$role])) {
            unset($this->data[$role][$member]);
        }
    }
}