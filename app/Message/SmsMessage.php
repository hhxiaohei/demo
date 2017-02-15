<?php
/**
 * Created by PhpStorm.
 * User: chita
 * Date: 16/10/8
 * Time: 下午5:04
 */

namespace App\Message;


interface SmsMessage
{

    /**
     * 得到模板id
     * @return mixed
     */
    public function getMobile();

    /**
     * 得到模板数据
     * @return mixed
     */
    public function getSMSData();
}