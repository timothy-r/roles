<?php
namespace Ace\Provider;

use Ace\Configuration;
use Silex\Application;
use Ace\Store\Factory as StoreFactory;
use Silex\ServiceProviderInterface;

/**
 * Provides the store for the application
 */
class StoreProvider implements ServiceProviderInterface
{

    public function register(Application $app)
    {
    }

    public function boot(Application $app)
    {
        $app['role.store'] = (new StoreFactory(new Configuration()))->create();
    }
}