<?php

namespace App\Message;

use Illuminate\Support\Arr;

/**
 * Created by PhpStorm.
 * User: chita
 * Date: 16/10/8
 * Time: 下午3:54
 */
class AuctionResMessage extends MessageBase implements WechatMessage
{
    public function getTemplate()
    {
        return 'kqCChOye2WLmuJ1jjoqXtYS21Jq0JApqcCO9lwHxeLo';
    }

    public function getWechatData()
    {
        return array_merge([
        ],array_only($this->getData(),['first','keyword1','keyword2','keyword3','remark']));
    }

    public function getOpenId()
    {
        return array_get($this->data, 'open_id');
    }

    public function getUrl()
    {
        return array_get($this->data, 'url');
    }

}