<?php namespace Ace\Provider;

use Silex\Application;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

use Symfony\Component\HttpFoundation\Response;
use Exception;


/**
 * Creates the correct responses from exceptions
 */
class ErrorHandlerProvider implements ServiceProviderInterface
{
    public function register(Container $app)
    {
        $app->error(function (Exception $e) use($app) {

            $app['logger']->addError($e->getMessage());

            $exception = get_class($e);
            $message = 'Error';
            $code = 500;

            switch ($exception) {
                case "Ace\Store\NotFoundException":
                    $message = $e->getMessage();
                    $code = 404;
                    break;
                case "Ace\Store\UnavailableException":
                    $message = "Database error";
                    $code = 503;
                    break;
            }

            return new Response(
                json_encode(['message' => $message]),
                $code,
                ['Content-Type' => 'application/json']);
        });

    }
}