<?php
namespace Ace\Store;

use Ace\Store\NotFoundException;
use Ace\Store\StoreInterface;

/**
 * @author timrodger
 * Date: 01/07/2017
 */
class RDBMSStore implements StoreInterface
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
    }

    /**
     * @param $role
     */
    public function get($role)
    {
    }

    /**
     * @return array
     */
    public function listAll()
    {
    }

    /**
     * @param $role
     */
    public function delete($role)
    {
    }
}