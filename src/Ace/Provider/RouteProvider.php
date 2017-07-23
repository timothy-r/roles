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
        $app->get("/roles", function(Request $req) use ($app){
            return $app['role.controller']->listRoles();
        });

        /**
         * Access a role - test it exists
         */
        $app->get("/roles/{role}", function(Request $req, $role) use ($app){
            return $app['role.controller']->getRole($role);
        })->assert('path', '.+');

        /**
         * Add a role
         */
        $app->put("/roles/{role}", function(Request $req, $role) use ($app) {
            return $app['role.controller']->addRole($role);
        })->assert('path', '.+');

        /**
         * Removes a role
         */
        $app->delete("/roles/{role}", function($role) use ($app) {
            return $app['role.controller']->deleteRole($role);
        })->assert('path', '.+');

        /**
         * List role members
         */
        $app->get("/roles/{role}/members", function(Request $req, $role) use ($app) {
            return $app['role.controller']->listRoleMembers($role);
        })->assert('path', '.+');

        /**
         * Add a member to a role
         */
        $app->put("/roles/{role}/members/{member}", function(Request $req, $role, $member) use ($app) {
            return $app['role.controller']->addMemberToRole($role, $member);
        })->assert('path', '.+');

        /**
         * Test if a member belongs to a role
         */
        $app->get("/roles/{role}/members/{member}", function(Request $req, $role, $member) use ($app) {
            return $app['role.controller']->memberBelongsToRole($role, $member);
        })->assert('path', '.+');

        /**
         * Remove a member from a role
         */
        $app->delete("/roles/{role}/members/{member}", function(Request $req, $role, $member) use ($app) {
            $app['role.controller']->removeMemberFromRole($role, $member);
        })->assert('path', '.+');
    }
}