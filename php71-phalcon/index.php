<?php

error_reporting(E_ALL);
ini_set('display_errors', 'On');

class ErrorneusRedis extends \Phalcon\Cache\Backend\Redis
{
    protected $_redis = null;
    protected $clusterMode = false;

    /**
     * Overriding to achieve Cluster functionality.
     *
     * Translated from:
     * https://github.com/phalcon/cphalcon/blob/83ba827b4019bfca7a9b76ec13933fa53ca99786/phalcon/cache/backend/redis.zep
     */
    public function _connect()
    {
        $options = $this->_options;

        $persistent = $options['persistent'];
        $host = $options['host'];

        if (!is_array($host)) {
            return parent::_connect();
        }
        $this->clusterMode = true;
//        $this->_redis = new \RedisCluster(null, $host, false, 0, $persistent);
        $this->_redis = new \RedisCluster(null, $host, 120, 100, $persistent);
    }
}


$cluster = ['redis-cluster:7000', 'redis-cluster:7001', 'redis-cluster:7002', 'redis-cluster:7003', 'redis-cluster:7004', 'redis-cluster:7005'];

$frontCache = new \Phalcon\Cache\Frontend\Data(array(
    "lifetime" => 3600
));

$redis = new ErrorneusRedis($frontCache, [
    'prefix' => 'test:',
    'persistent' => true,
    'host' => $cluster
]);

$redis->save('test', md5(mt_rand()), 300);
$result = $redis->get('test');
$redis->delete('test');

die($result);