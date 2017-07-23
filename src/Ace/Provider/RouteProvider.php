<?php
namespace Ace\Provider;

use Silex\Application;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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

            $app['logger']->info("Adding member '$member' to role '$role'");

            $app['role.store']->addMember($role, $member);

            return new Response(
                json_encode(["role" => $role, "member" => $member], JSON_UNESCAPED_SLASHES),
                200,
                ["Content-Type" => 'application/json']
            );
        })->assert('path', '.+');

        /**
         * Test if a member belongs to a role
         */
        $app->get("/roles/{role}/members/{member}", function(Request $req, $role, $member) use ($app) {

            $app['logger']->info("Getting member '$member' for role '$role'");

            if ($app['role.store']->memberBelongsToRole($role, $member)) {
                return new Response(
                    json_encode(["role" => $role, "member" => $member], JSON_UNESCAPED_SLASHES),
                    200,
                    ["Content-Type" => 'application/json']
                );
            } else {
                return new Response('{}', 404, ["Content-Type" => 'application/json']);
            }
        })->assert('path', '.+');

        /**
         * Remove a member from a role
         */
        $app->delete("/roles/{role}/members/{member}", function(Request $req, $role, $member) use ($app) {

            $app['logger']->info("Removing member '$member' from role '$role'");

            $app['role.store']->removeMember($role, $member);

            return new Response(
                json_encode('{}', JSON_UNESCAPED_SLASHES),
                200,
                ["Content-Type" => 'application/json']
            );
        })->assert('path', '.+');
    }
}