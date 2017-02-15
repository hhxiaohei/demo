<?php

namespace App\Events;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class MessageEvent extends Event
{
    use SerializesModels;

    const ALL = 0;

    const WECHAT = 1<<0;

    const MAIL = 1<<1;

    const SMS = 1<<2;

    public $template;

    public $data;

    public $ways;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($template,$data,$ways = self::ALL)
    {
        $this->template=$template;
        $this->data=$data;
        $this->ways=$ways;
    }
    public function getHandleData()
    {
        return app($this->template,[$this->data,$this->ways]);
    }

}
