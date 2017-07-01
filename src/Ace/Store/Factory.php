<?php
namespace Ace\Store;

use Ace\Configuration;
use Ace\Store\Memory as MemoryStore;
use Ace\Store\Unavailable as UnavailableStore;

/**
 * @author timrodger
 */
class Factory
{
    /**
     * @var Configuration
     */
    private $config;

    /**
     * @param Configuration $config
     */
    public function __construct(Configuration $config)
    {
        $this->config = $config;
    }

    /**
     * If an in-memory or unavailable store has been explicitly configured
     * then use that, otherwise use redis
     *
     * @return StoreInterface
     */
    public function create()
    {
        $dsn = $this->config->getStoreDsn();

        if ('MEMORY' == $dsn) {
            return new MemoryStore;
        } else if ('UNAVAILABLE' == $dsn) {
            return new UnavailableStore();
        } else {
            return new RDBMSStore($dsn);
        }
    }
}