<?php
namespace Ace\Store;

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
     *
     * @param $role
     */
    public function set($role)
    {
        try {
            $sql = "INSERT INTO 'roles' (name) VALUES('$role');";
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
            $sql = "SELECT * FROM 'roles' WHERE name = $role;";
            $results = $this->db->query($sql);
            if (count($results)){
                return $results[0];
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
            $sql = "SELECT 'name'' FROM 'roles';";
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
            $sql = "DELETE FROM 'roles' WHERE name = $role;";
            $this->db->exec($sql);
        } catch (PDOException $ex){
            throw new UnavailableException($ex->getMessage(), 503, $ex);
        }
    }
}