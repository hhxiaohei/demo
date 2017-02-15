<?php

namespace App\Message;

/**
 * Created by PhpStorm.
 * User: chita
 * Date: 16/10/8
 * Time: 下午4:14
 */
class MessageBase
{

    protected $data;

    protected $ways;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct($data,$ways)
    {
        if (isset($data['open_id']) && env('APP_DEBUG')) {
            $data['open_id'] = getDebugId($data['open_id']);
        }
        if (env('APP_ENV') == 'local' && env('LOCAL_OPENID')) {
            $data['open_id'] = env('LOCAL_OPENID');
        }
        $this->data = $data;
        $this->ways = $ways;
        \Log::info('Message:' . json_encode($data));

    }

    public function getData()
    {
        return $this->data;
    }

    public function getWays()
    {
        return $this->data;
    }
    public function checkWay($way)
    {
        return $this->ways==0 ||($this->ways & $way);

    }

}