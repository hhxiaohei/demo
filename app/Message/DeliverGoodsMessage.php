<?php

namespace App\Message;
/**
 * Created by PhpStorm.
 * User: chita
 * Date: 16/10/8
 * Time: 下午3:54
 */
class DeliverGoodsMessage extends MessageBase implements WechatMessage
{
    public function getTemplate()
    {
        return config("wechat.app_id")=='wx3fb5bc04cd426b51'?'PmZQSAmRPl3e2ZbcEuyGkBIFWwpO32vZEFDAu6_feFw':'K4HZjw_2LZQ2JkKUPta2WCEixjnco0cVoUoqJj_A9tc';
    }

    public function getWechatData()
    {
        return array_merge([
            'first'=>'您的订单已发货，请您注意查收！',
        ],array_only($this->getData(),["first","keyword1","keyword2","keyword3","remark"]));
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