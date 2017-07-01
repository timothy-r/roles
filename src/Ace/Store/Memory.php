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
        $this->data[] = $role;
    }

    /**
     * @param $role
     */
    public function get($role)
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
    public function listAll()
    {
        return $this->data;
    }

    /**
     * @param $role
     */
    public function delete($role)
    {
        unset($this->data[$role]);
    }
}