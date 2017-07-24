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
    public function setRole($role, $description = '')
    {
        try {
            $sql = "INSERT INTO roles (name, description) VALUES('$role', '$description');";
            $this->db->exec($sql);
        } catch (PDOException $ex){
            if ('23505' == $ex->getCode()){
                // unique_violation, role exists, set description
                $sql = "UPDATE roles SET description = '$description' WHERE name = '$name'";
                $this->db->exec($sql);
            } else {
                throw new UnavailableException($ex->getMessage(), null, $ex);
            }
        }
    }

    /**
     * @param $role
     */
    public function getRole($role)
    {
        try {
            $sql = "SELECT id, name, description FROM roles WHERE name = '$role';";
            $results = $this->db->query($sql);
            if ($roleData = $results->fetch(PDO::FETCH_ASSOC)){
                return $roleData;
            } else {
                throw new NotFoundException("Role '$role' does not exist'");
            }
        } catch (PDOException $ex){
            throw new UnavailableException($ex->getMessage(), null, $ex);
        }
    }

    /**
     * @return array of roles
     */
    public function listRoles()
    {
        try {
            $sql = "SELECT * FROM roles;";
            return $this->db->query($sql, PDO::FETCH_ASSOC);
        } catch (PDOException $ex){
            throw new UnavailableException($ex->getMessage(), null, $ex);
        }
    }

    /**
     * @param $role
     */
    public function deleteRole($role)
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
    public function getRoleMembers($role)
    {
        try {
            $roleData = $this->getRole($role);

            $sql = sprintf(
                "SELECT * FROM members WHERE id in (SELECT member_id from roles_members where role_id = %d);",
                $roleData['id']
            );

            return $this->db->query($sql, PDO::FETCH_ASSOC) ;
        } catch (PDOException $ex){
            throw new UnavailableException($ex->getMessage(), null, $ex);
        }

    }

    /**
     * @param $role
     * @param $member
     */
    public function addMemberToRole($role, $member)
    {
        try {
            // test if role exists, throws exception if missing
            $roleData = $this->getRole($role);

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
            $addMemberToRoleSql = sprintf("INSERT INTO roles_members(member_id , role_id)
                VALUES((SELECT id FROM members WHERE name = '$member'), %d);",
                $roleData['id']
            );

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
            $roleData = $this->getRole($role);
            $memberData = $this->getMember($member);

            $sql = sprintf("SELECT * FROM roles_members
              WHERE role_id = %d
              AND member_id = %d;",
                $roleData['id'],
                $memberData['id']);
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
    public function removeMemberFromRole($role, $member)
    {
        try {
            // test if role & member exist, throws exception if missing
            $roleData = $this->getRole($role);
            $memberData = $this->getMember($member);

            $sql = sprintf("DELETE FROM roles_members
              WHERE role_id = %d
              AND member_id = %d;",
                $roleData['id'],
                $memberData['id']
            );
            $this->db->query($sql);

        } catch (PDOException $ex){
            throw new UnavailableException($ex->getMessage(), null, $ex);
        }
    }

    /**
     * @param $member
     */
    public function listMemberRoles($member)
    {
        try {
            $memberData = $this->getMember($member);

            $sql = sprintf("SELECT * FROM roles WHERE id in (SELECT role_id from roles_members where member_id = %d);",
                $memberData['id']
            );

            return $this->db->query($sql, PDO::FETCH_ASSOC);
        } catch (PDOException $ex){
            throw new UnavailableException($ex->getMessage(), null, $ex);
        }
    }
}