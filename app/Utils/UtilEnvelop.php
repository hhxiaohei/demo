<?php
/**
 * Created by PhpStorm.
 * User: yangcuiwang
 * Date: 2016/10/9
 * Time: 下午1:07
 */

namespace App\Utils;


class UtilEnvelop
{

    function __construct(){}

    public static function config(){
        return config('envelope_message');
    }

    /**
     * @param $id
     */
    public static function getById($id){
        $data = self::config();
        if(isset($data[$id])){
            return $data[$id];
        }else{
            return null;
        }
    }

    /**
     * @return mixed
     * 获取所有
     */
    public static function getAll(){
        return self::config();
    }

    /**
     * @return int
     * 统计有多少个数据
     */
    public static function count(){
        return count(self::config());
    }

    /**
     * @return int
     * 随机获取
     */
    public static function rands(){
        return rand(0,self::count()-1);
    }
}