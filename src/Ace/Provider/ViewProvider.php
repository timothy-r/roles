<?php namespace Ace\Provider;

use Pimple\ServiceProviderInterface;
use Pimple\Container;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Adds view handler to the application
 */
class ViewProvider implements ServiceProviderInterface
{
    public function register(Container $app)
    {
        /**
         * view handler to convert raw data from the controller into formatted response body
         */
        $app->view(function (array $controllerResult, Request $request) use ($app) {

            return new JsonResponse($controllerResult);

            /**
             * implement conn neg later
             */

            /**
            $bestFormat = $app['negotiator']->getBest($request->headers->get('Accept'), array('application/json', 'application/xml'));

            if ('application/json' === $bestFormat->getValue()) {
                return new JsonResponse($controllerResult);
            }

            if ('application/xml' === $bestFormat->getValue()) {
                return $app['serializer.xml']->renderResponse($controllerResult);
            }

            return new Response(print_r($controllerResult, 1));
             */
        });
    }
}
