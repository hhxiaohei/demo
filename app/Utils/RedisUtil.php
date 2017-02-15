<?php namespace App\Utils;

use Illuminate\Support\Facades\Redis;

class RedisUtil {

    /**
     * @param $key
     * @param $value
     * @param int $expire expire time in seconds
     */
    public static function set($key, $value, $expire=0)
    {
        if($expire){
            Redis::setex($key, $expire, $value);
        }else{
            Redis::set($key, $value);
        }
    }

    public static function get($key)
    {
        return Redis::get($key);
    }

    public static function del($key)
    {
        return Redis::del($key);
    }

    public static function incrByFloat($key, $value)
    {
        return Redis::INCRBYFLOAT($key, $value);
    }

    /**
     * get time of the key to be expired
     * @param $key
     * @return mixed
     */
    public static function ttl($key)
    {
        return Redis::ttl($key);
    }

    /**
     * get length of list key
     * @param $key
     * @return mixed
     */
    public static function llen($key)
    {
        return Redis::llen($key);
    }

    public static function lrange($key, $start = 0, $end = 100)
    {
        return Redis::lrange($key, $start, $end);
    }

}