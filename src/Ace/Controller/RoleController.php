<?php namespace Ace\Controller;

use Ace\Store\NotFoundException;
use Ace\Store\StoreInterface;
use Monolog\Logger;
use Symfony\Component\HttpFoundation\Request;

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
        $result = [];
        foreach($this->store->listAll() as $roleData){
            $result []= $this->convertRoleIdToUrl($roleData);
        }
        return $result;
    }

    /**
     * @param $role
     * @return mixed
     */
    public function getRole($role)
    {
        $this->logger->info("Getting '$role'");
        // replace db id with url
        $roleData = $this->store->get($role);
        $roleData = $this->convertRoleIdToUrl($roleData);
        return $roleData;
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
        $roleData = ['name' => $role, 'description' => $description];
        $roleData = $this->convertRoleIdToUrl($roleData);
        // return url & role data
        return $roleData;
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

    /**
     * @param $role
     * @return array
     */
    public function listRoleMembers($role)
    {
        $this->logger->info("List members of role '$role'");
        $members = $this->store->getMembers($role);
        // add member urls
        return $members;
    }

    /**
     * @param $role
     * @param $member
     * @return array
     */
    public function addMemberToRole($role, $member)
    {
        $this->logger->info("Adding member '$member' to role '$role'");
        $this->store->addMember($role, $member);
        return ["role" => $role, "member" => $member];
    }

    /**
     * @param $role
     * @param $member
     * @return array
     */
    public function memberBelongsToRole($role, $member)
    {
        $this->logger->info("Getting member '$member' for role '$role'");

        if ($this->store->memberBelongsToRole($role, $member)) {
            return ["role" => $role, "member" => $member];
        } else {
            throw new NotFoundException("Member '$member' does not belong to role '$role'");
        }
    }

    /**
     * @param $role
     * @param $member
     * @return string
     */
    public function removeMemberFromRole($role, $member)
    {
        $this->logger->info("Removing member '$member' from role '$role'");
        $this->store->removeMember($role, $member);
        return '';
    }

    /**
     * move to a helper class?
     *
     * @param array $roleData
     * @return array
     */
    private function convertRoleIdToUrl(array $roleData)
    {
        if (isset($roleData['id'])){
            $roleData['url'] = 'https://role.service.net/roles/' . $roleData['name'];
            unset($roleData['id']);
        }

        return $roleData;
    }
}