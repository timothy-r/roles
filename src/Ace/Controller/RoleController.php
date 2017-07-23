<?php namespace Ace\Controller;

use Ace\Store\StoreInterface;

/**
 * Class RoleController
 * @package Ace\Controller
 */
class RoleController
{

    /**
     * @var StoreInterface
     */
    private $store;

    /**
     * RoleController constructor.
     * @param StoreInterface $store
     */
    public function __construct(StoreInterface $store)
    {
        $this->store = $store;
    }

    /**
     * @param int $start
     * @param null $number
     * @return array
     */
    public function listRoles($start = 0, $number = null)
    {
        return $this->store->listAll();
    }
}