<?php
/**
 * User: Mani Wang
 * Date: 10/28/16
 * Time: 4:06 PM
 * Email: mani@forone.co
 */

namespace App\Utils;


use Aliyun\SLS\Client;
use Aliyun\SLS\Models\LogItem;
use Aliyun\SLS\Requests\PutLogsRequest;

class AliLog
{
    private static function getConfig()
    {
        return [
            'AccessKeyId'     => env('AccessKeyId'),
            'AccessKeySecret' => env('AccessKeySecret'),
            'endpoint'        => env('endpoint'),
            'project'         => env('project'),
            'logStore'        => env('logStore'),
            'topic'           => env('topic', ''),
            'source'          => env('source', ''),
        ];
    }

    /**
     * 记录日志信息到阿里云日志分析系统
     * @param array  $items 内容数组,数组项为数组时会自动拆分单个数组项为日志进行提交
     * @param string $topic 用以检索
     * @param string $source 用以检索
     * @return \Aliyun\SLS\Responses\PutLogsResponse|string
     * @throws \Aliyun\SLS\Exception
     */
    public static function log($items = [], $topic = '', $source = '')
    {
        if (empty($items)) {
            return '';
        }

        $logs = [];
        if (is_array(array_first($items))) {
            foreach ($items as $item) {
                $logs[] = new LogItem($item);
            }
        } else {
            $logs[] = new LogItem($items);
        }
        $config = self::getConfig();
        $request = new PutLogsRequest($config['project'], $config['logStore'], $config['topic'], $config['source'],
            $logs);
        $client = new Client($config['endpoint'], $config['AccessKeyId'], $config['AccessKeySecret']);

        return $client->putLogs($request);
    }
}