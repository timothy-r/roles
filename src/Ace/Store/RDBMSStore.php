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
            throw new UnavailableException($ex->getMessage(), 503, $ex);
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
                throw new NotFoundException;
            }
        } catch (PDOException $ex){
            throw new UnavailableException($ex->getMessage(), 503, $ex);
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
            throw new UnavailableException($ex->getMessage(), 503, $ex);
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
            throw new UnavailableException($ex->getMessage(), 503, $ex);
        }
    }
}