<?php

namespace App\Message;
/**
 * Created by PhpStorm.
 * User: chita
 * Date: 16/10/8
 * Time: 下午3:54
 */
class OrderStatusMessage extends MessageBase implements WechatMessage
{
    public function getTemplate()
    {
        return config("wechat.app_id")=='wx3fb5bc04cd426b51'?'PmZQSAmRPl3e2ZbcEuyGkBIFWwpO32vZEFDAu6_feFw':'FRaCGzy3B1AYGhu_GR0EzLlP8jYZ0jw1JYf7SvPNjfc';
    }

    public function getWechatData()
    {
        return array_merge([
            'first'=>'您的订单已加工包装完成！'
        ],array_only($this->getData(),["first","OrderSn","OrderStatus","remark"]));
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