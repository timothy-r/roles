<?php namespace Ace\Provider;

use Ace\Controller\RoleController;
use Pimple\ServiceProviderInterface;
use Pimple\Container;

/**
* Provides the role controller
 */
class RoleControllerProvider implements ServiceProviderInterface
{
    public function register(Container $app)
    {
        $app['role.controller'] = new RoleController($app['role.store'], $app['logger']);
    }
}
