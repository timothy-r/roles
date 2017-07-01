<?php
namespace Ace\Provider;

use Ace\Configuration;
use Silex\Application;
use Ace\Store\Factory as StoreFactory;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

/**
 * Provides the store for the application
 */
class StoreProvider implements ServiceProviderInterface
{

    public function register(Container $app)
    {
        $app['role.store'] = (new StoreFactory(new Configuration()))->create();
    }
}