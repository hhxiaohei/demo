<?php
/**
 * Created by PhpStorm.
 * User: chita
 * Date: 16/10/8
 * Time: 下午4:51
 */

namespace App\Message;


interface MailMessage
{
    /**
     * 得到模板id
     * @return mixed
     */
    public function getMailView();

    /**
     * 得到模板数据
     * @return mixed
     */
    public function getMailData();
    /**
     * 得到目标openid
     * @return mixed
     */
    public function getEmail();
}