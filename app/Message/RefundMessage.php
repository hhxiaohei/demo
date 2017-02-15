<?php

namespace App\Message;

use Illuminate\Support\Arr;

/**
 * Created by PhpStorm.
 * User: chita
 * Date: 16/10/8
 * Time: 下午3:54
 */
class RefundMessage extends MessageBase implements WechatMessage
{
    public function getTemplate()
    {
        return 'muU2s7iOW6LdDiQhyG2a6ry52dHcq2U8-Lekho9_N74';
    }

    public function getWechatData()
    {
        return array_merge([
            'first'=>'您的订单已发货，请您注意查收！',
        ],array_only($this->getData(),["first","reason","refund","remark"]));
    }

    public function getOpenId()
    {
        return $this->getData()["open_id"];
    }

    public function getUrl()
    {
        return Arr::get($this->getData(),"url","");
    }

}