<?php namespace Ace\Controller;

use Ace\Store\StoreInterface;
use Monolog\Logger;

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
     * @var Logger
     */
    private $logger;

    /**
     * RoleController constructor.
     * @param StoreInterface $store
     */
    public function __construct(StoreInterface $store, Logger $logger)
    {
        $this->store = $store;
        $this->logger = $logger;
    }

    /**
     * @param int $start
     * @param null $number
     * @return array
     */
    public function listRoles($start = 0, $number = null)
    {
        $this->logger->info("Getting list of roles");
        // replace db ids with urls
        return $this->store->listAll();
    }

    /**
     * @param $role
     * @return mixed
     */
    public function getRole($role)
    {
        $this->logger->info("Getting '$role'");
        // replace db id with url
        return $this->store->get($role);
    }

    /**
     * @param $role
     * @param string $description
     * @return array
     */
    public function addRole($role, $description = '')
    {
        $this->logger->info("Adding role '$role'");
        $this->store->set($role);
        $data = ['name' => $role, 'description' => $description];

        // return url & role data
        return $data;
    }

    /**
     * @param $role
     */
    public function deleteRole($role)
    {
        $this->logger->info("Removing role '$role'");
        $this->store->delete($role);
        return '';
    }
}