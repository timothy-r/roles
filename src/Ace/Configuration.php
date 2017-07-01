<?php
namespace Ace;

/*
 * @author timrodger
 */
class Configuration
{

    /**
     * @return string
     */
    public function getStoreDsn()
    {
        return getenv('STORE_DSN');
    }
}