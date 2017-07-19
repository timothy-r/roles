<?php
namespace Ace\Store;

use Ace\Store\NotFoundException;
use Ace\Store\UnavailableException;
use Ace\Store\StoreInterface;
use PDO;
use PDOException;

/**
 * @author timrodger
 * Date: 01/07/2017
 */
class RDBMSStore implements StoreInterface
{
    /**
     * @var PDO
     */
    private $db;

    /**
     * @param PDO $db
     */
    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    /**
     * Create a role if one does not exist
     *
     * @param $role
     */
    public function set($role)
    {
        try {
            $exists = $this->get($role);
            return true;

        } catch (NotFoundException $ex) {
            // role does not exist so create it
        }

        try {
            $sql = "INSERT INTO roles (name) VALUES('$role');";
            $this->db->exec($sql);
        } catch (PDOException $ex){
            throw new UnavailableException($ex->getMessage(), null, $ex);
        }
    }

    /**
     * @param $role
     */
    public function get($role)
    {
        try {
            $sql = "SELECT name FROM roles WHERE name = '$role';";
            $results = $this->db->query($sql);
            $rows = $results->fetchAll();
            if (count($rows)){
                return $rows[0];
            } else {
                throw new NotFoundException("Role '$role' does not exist'");
            }
        } catch (PDOException $ex){
            throw new UnavailableException($ex->getMessage(), null, $ex);
        }
    }

    /**
     * @return array of role names
     */
    public function listAll()
    {
        try {
            $roles = [];
            $sql = "SELECT name FROM roles;";
            foreach($this->db->query($sql) as $result) {
                $roles[] = $result['name'];
            }
            return $roles;
        } catch (PDOException $ex){
            throw new UnavailableException($ex->getMessage(), null, $ex);
        }
    }

    /**
     * @param $role
     */
    public function delete($role)
    {
        try {
            $sql = "DELETE FROM roles WHERE name = '$role';";
            $this->db->exec($sql);
        } catch (PDOException $ex){
            throw new UnavailableException($ex->getMessage(), null, $ex);
        }
    }

    /**
     * @param $role
     */
    public function getMembers($role)
    {
        try {
            $this->get($role);
            $members = [];

            $sql = "SELECT name FROM members WHERE id in (SELECT member_id from roles_members where role_id = (select id from roles where name = '$role')); ";
            foreach($this->db->query($sql) as $result) {
                $members[] = $result['name'];
            }
            return $members;
        } catch (PDOException $ex){
            throw new UnavailableException($ex->getMessage(), null, $ex);
        }

    }

    /**
     * @param $role
     * @param $member
     */
    public function addMember($role, $member)
    {
        try {
            // test if role exists, throws exception if missing
            $this->get($role);

            // ensure member exists
            $addMemberSql = "INSERT INTO members (name) VALUES('$member');";
            $this->db->exec($addMemberSql);
        } catch (PDOException $ex){
            if ('23505' == $ex->getCode()){
                // unique_violation, member exists, so continue
            } else {
                throw new UnavailableException($ex->getMessage(), null, $ex);
            }
        }

        try {
            // insert roles_members bond
            $addMemberToRoleSql = "INSERT INTO roles_members(member_id , role_id)
                VALUES((SELECT id FROM members WHERE name = '$member'), (SELECT id FROM roles WHERE name = '$role'));";

            $this->db->exec($addMemberToRoleSql);

        } catch (PDOException $ex){
            if ('23505' == $ex->getCode()){
                // unique_violation, so the role to member relation already exists, success
            } else {
                throw new UnavailableException($ex->getMessage(), null, $ex);
            }
        }
    }

    /**
     * @param $member
     * @return array ['id' => id, 'name' => name]
     */
    private function getMember($member)
    {
        $sql = "SELECT * FROM members WHERE name = '$member';";
        $results = $this->db->query($sql);
        $rows = $results->fetchAll();
        if (count($rows)){
            return $rows[0];
        } else {
            throw new NotFoundException("Member '$member' does not exist'");
        }
    }

    /**
     * @param $role
     * @param $member
     */
    public function memberBelongsToRole($role, $member)
    {
        try {
            // test if role & member exist, throws exception if missing
            $this->get($role);
            $this->getMember($member);

            $sql = "SELECT * FROM roles_members
              WHERE role_id = (SELECT id FROM roles WHERE name = '$role')
              AND member_id = (SELECT id FROM members WHERE name = '$member');";
            $results = $this->db->query($sql);

            return ($results->rowCount() === 1);

        } catch (PDOException $ex){
            throw new UnavailableException($ex->getMessage(), null, $ex);
        }
    }

    /**
     * @param $role
     * @param $member
     */
    public function removeMember($role, $member)
    {
        try {
            // test if role & member exist, throws exception if missing
            $this->get($role);
            $this->getMember($member);

            $sql = "DELETE FROM roles_members
              WHERE role_id = (SELECT id FROM roles WHERE name = '$role')
              AND member_id = (SELECT id FROM members WHERE name = '$member');";
            $this->db->query($sql);

        } catch (PDOException $ex){
            throw new UnavailableException($ex->getMessage(), null, $ex);
        }
    }
}