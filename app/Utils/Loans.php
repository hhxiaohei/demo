<?php
/**
 * Created by PhpStorm.
 * User: yangcuiwang
 * Date: 2016/10/9
 * Time: 下午1:07
 */

namespace App\Utils;

use App\Models\IndustryType;

class Loans
{
    public static function data($type = null, $id = null)
    {

        $configData = config('loans');

        $pledge_items = [];
        $pledge_options = [];
        $data = IndustryType::where('enabled', 1)->get();

        foreach ($data as $key => $value) {

            $pledge_options[] = $value->name;

            $config = explode('|', $value->config);

            foreach ($config as $k => $item) {

                if(strstr($item,"时间")){
                    $items[] = ['label' => $item, 'name' => $item,'value' => '', 'type' => 'date'];
                }elseif(strstr($item,"=")){
                    $names =  explode('=', $item);
                    $options = explode(" ",$names[1]);
                    $items[] = ['label' => $names[0],'name' => $names[0], 'value' => '', 'type' => 'select', 'options' => $options];
                }else{
                    $items[] = ['label' => $item,'name' => $item, 'value' => '', 'type' => 'text'];
                }

            }

            $pledge_items[] = [
                'id'    => $value->id,
                'key'   => $value->name,
                'items' => [
                    $items
                ]
            ];
        }

        $configData['pledge']['items'] = [
            [
                [
                    "label"       => '行业数据',
                    'name'        => '行业数据',
                    'type'        => 'selectBinding',
                    'options'     => $pledge_options,
                    'extra_items' => $pledge_items
                ]
            ]
        ];

        if ($type) {
            return $configData[$type];
        } else {
            return $configData;
        }
    }
}