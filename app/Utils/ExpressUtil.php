<?php
/**
 * Created by PhpStorm.
 * User: yangcuiwang
 * Date: 2016/10/9
 * Time: 下午1:07
 */

namespace App\Utils;


class ExpressUtil
{
    /**
     * @desc 获取快递信息
     * @param string $code 快递代号
     * @param string $post 快递单号
     */
    public static function getExpressInfo($post, $code='shunfeng')
    {
        $url = "http://m.kuaidi100.com/query?type={$code}&postid={$post}&id=1&valicode=&temp=" . rand(1, 710);
        $header = [
            'User-Agent:Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/53.0.2785.116 Safari/537.36',
        ];
        $body = self::getcontent($url, $header);
        $resultData = json_decode($body, true);

        return $resultData;
    }

    private static function getcontent($url, $header = null)
    {
        $timeout = 10;
        $ch = curl_init();
        //设置选项，包括URL
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, false);

        if ($header) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        }

        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        //执行并获取HTML文档内容
        $file_contents = curl_exec($ch);
        //释放curl句柄
        curl_close($ch);
        //打印获得的数据
        return $file_contents;
    }
}