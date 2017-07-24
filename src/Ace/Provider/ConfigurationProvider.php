<?php namespace Ace\Provider;

use Ace\Configuration;
use Pimple\ServiceProviderInterface;
use Pimple\Container;

/**
 * @author timrodger
 * Date: 23/07/2017
 */
class ConfigurationProvider implements ServiceProviderInterface
{
    public function register(Container $app)
    {
        $app['config'] = function() use ($app) {
            return new Configuration();
        };
    }
}