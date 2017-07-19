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
        $host = getenv('RDS_HOSTNAME');
        $port = getenv('RDS_PORT');
        $db_name = getenv('RDS_DB_NAME');

        if (empty($port) && empty($db_name)) {
            return $host;
        }

        // need to configure db type too
        return "pgsql:host=$host;port=$port;dbname=$db_name";
    }

    /**
     * @return string
     */
    public function getStoreUserName()
    {
        return getenv('RDS_USERNAME');
    }

    /**
     * @return string
     */
    public function getStorePassword()
    {
        return getenv('RDS_PASSWORD');
    }
}