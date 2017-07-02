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

            $app['logger']->info("Getting list of roles");

            $roles = $app['role.store']->listAll();
            return new Response(json_encode($roles, JSON_UNESCAPED_SLASHES), 200, ["Content-Type" => 'application/json']);

        });

        /**
         * Access a role - test it exists
         */
        $app->get("/roles/{role}", function(Request $req, $role) use ($app){

            $app['logger']->info("Getting '$role'");

            $role = $app['role.store']->get($role);
            return new Response(json_encode([$role], JSON_UNESCAPED_SLASHES), 200, ["Content-Type" => 'application/json']);

        });

        /**
         * Add a role
         */
        $app->put("/roles/{role}", function(Request $req, $role) use ($app) {

            $app['logger']->info("Setting role '$role'");

            $app['role.store']->set($role);

            return new Response(json_encode(["role" => $role], JSON_UNESCAPED_SLASHES), 200, ["Content-Type" => 'application/json']);

        });

        /**
         * Removes a role
         */
        $app->delete("/roles/{role}", function($role) use ($app) {

            $app['logger']->info("Removing role '$role'");

            $app['role.store']->delete($role);

            return new Response('', 200);

        });

    }
}