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
    public function setRole($role)
    {
        if (!isset($this->data[$role])) {
            $this->data[$role] = ['name' => $role];
        }
    }

    /**
     * @param $role
     */
    public function getRole($role)
    {
        if (isset($this->data[$role])) {
            return $this->data[$role];
        } else {
            throw new NotFoundException("Role '$role' not found");
        }
    }

    /**
     * @return array
     */
    public function listRoles()
    {
        return array_values($this->data);
    }

    /**
     * @param $role
     */
    public function deleteRole($role)
    {
        unset($this->data[$role]);
    }

    /**
     * @param $role
     */
    public function getRoleMembers($role)
    {
        if (isset($this->data[$role])) {
            return array_keys($this->data[$role]);
        } else {
            throw new NotFoundException("Role '$role' not found");
        }
    }

    /**
     * @param $role
     * @param $member
     */
    public function addMemberToRole($role, $member)
    {
        // ensure role exists
        $this->setRole($role);

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
        } else {
            throw new NotFoundException("Role '$role' not found");
        }
    }

    /**
     * @param $role
     * @param $member
     */
    public function removeMemberFromRole($role, $member)
    {
        if (isset($this->data[$role])) {
            unset($this->data[$role][$member]);
        }
    }

    public function listMemberRoles($member)
    {

    }
}