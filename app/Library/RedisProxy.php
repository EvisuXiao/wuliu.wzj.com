<?php
/**
 * Created by PhpStorm.
 * User: xiaowenbin
 * Date: 2018/07/20
 * Time: 16:13
 */
namespace App\Library;


use Illuminate\Support\Facades\Redis;

class RedisProxy
{
    private $_redis = null;

    public function __construct($connection = '') {
        $this->_redis = Redis::connection($connection);
    }

    public function get($key) {
        return $this->_redis->get($key);
    }

    public function set($key, $value, $timeout = 0) {
        return $timeout > 0 ? $this->_redis->set($key, $value, 'EX', $timeout) : $this->_redis->set($key, $value);
    }

    public function __call($method, $params) {
        return call_user_func_array([$this->_redis, $method], $params);
    }
}