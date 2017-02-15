<?php
/**
 * User: Mani Wang
 * Date: 10/28/16
 * Time: 4:06 PM
 * Email: yangcuiwang@nxdai.com
 */

namespace App\Utils;

class BankCode
{
    public static $banks = [
        'ABOC'  => '中国农业银行',
        'BKCH'  => '中国银行',
        'CIBK'  => '中信银行',
        'FJIB'  => '兴业银行',
        'GDBK'  => '广发银行',
        'HXBK'  => '华夏银行',
        'ICBK'  => '中国工商银行',
        'MSBC'  => '中国民生银行',
        'PCBC'  => '中国建设银行',
        'PSBC'  => '中国邮政储蓄银行',
        'SZDB'  => '平安银行',
        'SPDB'  => '浦发银行',
        'BJCN'  => '北京银行',
        'CMBC ' => '招商银行',
        'COMM ' => '交通银行',
    ];

    public static function bank_select()
    {
        $result = array();
        foreach (static::$banks as $key => $value) {
            array_push($result, ['label' => $value->name, 'value' => $value->id]);
        }
        return $result;
    }

    public static function get_name($key){
        return static::$banks[$key];
    }

    public static function getBanks(){
        return static::$banks;
    }
}