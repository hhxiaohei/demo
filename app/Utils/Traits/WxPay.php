<?php namespace App\Utils\Traits;
/**
 * User: Mani Wang
 * Date: 06/01/2017
 * Time: 1:52 PM
 * Email: mani@forone.co
 */
trait WxPay
{
    private function getPayment()
    {
        if (env('APP_DEBUG')) {
            return getTestWxApplication()->payment;
        } else {
            return app('wechat')->payment;
        }
    }

    public function refund($outTradeNo, $refundId, $totalFee, $refundFee = '', $operator = '')
    {
        return $this->getPayment()->refund($outTradeNo, $refundId, $totalFee, $refundFee, $operator);
    }
}