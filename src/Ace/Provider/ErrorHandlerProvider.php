<?php namespace Ace\Provider;

use Silex\Application;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

use Symfony\Component\HttpFoundation\Response;
use Exception;

/**
 * Handles exceptions
 */
class ErrorHandlerProvider implements ServiceProviderInterface
{
    public function register(Container $app)
    {
        $app->error(function (Exception $e) use($app) {

            $app['logger']->addError($e->getMessage());

            return new Response($e->getMessage(), $e->getCode());
        });

    }
}