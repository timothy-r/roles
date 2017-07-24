<?php
namespace Ace\Store;

/**
 * Interface to accessing roles
 *
 * @package Ace\Store
 */
interface StoreInterface
{
    public function setRole($role);

    public function getRole($role);

    public function listRoles();

    public function deleteRole($role);

    public function getRoleMembers($role);

    public function addMemberToRole($role, $member);

    public function memberBelongsToRole($role, $member);

    public function removeMemberFromRole($role, $member);
}