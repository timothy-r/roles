<?php
namespace Ace\Provider;

use Silex\Application;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

use Symfony\Component\HttpFoundation\Request;

/**
 * Configures routing
 */
class RouteProvider implements ServiceProviderInterface
{
    /**
     * @param Application $app
     */
    public function register(Container $app)
    {
        /**
         * Respond with a list of roles
         */
        $app->get("/roles", 'role.controller:listRoles');

        /**
         * Access a role - test it exists
         */
        $app->get("/roles/{role}", 'role.controller:getRole')->assert('path', '.+');

        /**
         * Add a role
         */
        $app->put("/roles/{role}", 'role.controller:addRole')->assert('path', '.+');

        /**
         * Removes a role
         */
        $app->delete("/roles/{role}", 'role.controller:deleteRole')->assert('path', '.+');

        /**
         * List role members
         */
        $app->get("/roles/{role}/members", 'role.controller:listRoleMembers')->assert('path', '.+');

        /**
         * Add a member to a role
         */
        $app->put("/roles/{role}/members/{member}", 'role.controller:addMemberToRole')->assert('path', '.+');

        /**
         * Test if a member belongs to a role
         */
        $app->get("/roles/{role}/members/{member}", 'role.controller:memberBelongsToRole')->assert('path', '.+');

        /**
         * Remove a member from a role
         */
        $app->delete("/roles/{role}/members/{member}", 'role.controller:removeMemberFromRole')->assert('path', '.+');
    }
}