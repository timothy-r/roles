<?php
namespace Ace\Store;

/**
 * Interface to accessing roles
 *
 * @package Ace\Store
 */
interface StoreInterface
{
    public function set($role);

    public function get($role);

    public function listAll();

    public function delete($role);

    public function getMembers($role);

    public function addMember($role, $member);

    public function memberBelongsToRole($role, $member);

    public function removeMember($role, $member);
}