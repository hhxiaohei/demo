<?php

namespace App\Listeners;

use App\Events\MessageEvent;
use App\Message\MailMessage;
use App\Message\SmsMessage;
use App\Message\WechatMessage;
use Illuminate\Support\Facades\Mail;

class MessageHandle
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  Message  $event
     * @return void
     */
    public function handle(MessageEvent $event)
    {
        $data = $event->getHandleData();
        if($data->checkWay(MessageEvent::WECHAT) && $data instanceof WechatMessage)
        {
            try{
                if (env('APP_DEBUG')) {
                    $notice = getTestWxApplication()->notice;
                }else{
                    $notice = app('wechat')->notice;
                }
                $notice->to($data->getOpenId())->uses($data->getTemplate())->data($data->getWechatData())->url($data->getUrl())->send();
            }catch (\Exception $e){
                \Log::error($e);
            }
        } elseif ($data->checkWay(MessageEvent::MAIL) && $data instanceof MailMessage) {
            $mail = $data->getMailData();
            $mailAddress = $data->getEmail();
            $view = $data->getMailView();
            $messageHandle = function ($message)use($mail,$mailAddress) {
                $messageData = $mail["message"];
                if(!empty($messageData)){
                    foreach($messageData as $k=>$v){
                        call_user_func_array([$message,$k],is_array($v)?$v:[$v]);
                    }
                }
            };
            if($view){
                Mail::send( $view,$mail["content"],$messageHandle );
            }else{
                Mail::raw( $mail["content"], $messageHandle);
            }
        }
        elseif ($data->checkWay(MessageEvent::SMS) && $data instanceof SmsMessage) {
            //            $result = PhpSms::make()->to($data->getMobile())->content($data->getSMSData())->send();
        }
    }
}
