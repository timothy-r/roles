<?php
namespace Ace\Store;

use Exception;

/**
 * @author timrodger
 */
class UnavailableException extends Exception {

    public function __construct($message = "", $code = 0, Exception $previous = null)
    {
        parent::__construct($message, 500, $previous);
    }
}