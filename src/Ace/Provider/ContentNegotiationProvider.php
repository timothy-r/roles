<?php namespace Ace\Provider;

use Negotiation\Negotiator;
use Pimple\ServiceProviderInterface;
use Pimple\Container;

/**
 * Provide a content negotiation service
 */
class ContentNegotiationProvider implements ServiceProviderInterface
{
    public function register(Container $app)
    {
        $app['negotiator'] = new Negotiator();
    }
}