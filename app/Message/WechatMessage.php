<?php
/**
 * Created by PhpStorm.
 * User: chita
 * Date: 16/10/8
 * Time: 下午4:35
 */

namespace App\Message;


interface WechatMessage
{

    /**
     * 得到模板id
     * @return mixed
     */
    public function getTemplate();

    /**
     * 得到模板数据
     * @return mixed
     */
    public function getWechatData();
    /**
     * 得到目标openid
     * @return mixed
     */
    public function getOpenId();

    /**
     * 得到url
     *
     */
    public function getUrl();
}