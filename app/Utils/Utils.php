<?php

namespace App\Utils;


use Carbon\Carbon;
use DateTime;
use Hashids\Hashids;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;


class Utils {

    public static function validateDate($date, $format = 'Y-m-d')
    {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }

    public static function getDateFromExcel($excelDate)
    {
        return Carbon::createFromFormat("Y-m-d H:i:s", gmdate("Y-m-d H:i:s", ($excelDate - 25569) * 86400));
    }

    /**
     * 根据数字返回中文简体序号
     * @param $num
     * @return array
     */
    public static function getNumCn($num)
    {
        $arr = ['零', '一', '二', '三', '四', '五', '六', '七', '八', '九', '十'];

        return $num <= 10 ? $arr[$num] : $arr[floor($num / 10) == 1 ? 10 : floor($num / 10)] . $arr[$num % 10];
    }

    public static function getAddressByIp($ip)
    {

        $result = '';
        try {
            $result = \Qiniu\json_decode(file_get_contents('http://api.map.baidu.com/location/ip?ak='.config('nxd.baidu_key').'&ip='.$ip));
            if (!$result->status) {
                $result = $result->content->address;
            }
        } catch (\Exception $e) {
            Log::error('LocationError:' . json_encode($e));
        }
        return $result;
    }

    public static function getLocationInfo($lat, $lng)
    {
        $result = '';
        try {
            $result = \Qiniu\json_decode(file_get_contents('http://api.map.baidu.com/geocoder/v2/?ak='.config('nxd.baidu_key').'&location='.$lat.','.$lng.'&output=json&pois=0'));
            if (!$result->status) {
                $result = $result->result;
            }
        } catch (\Exception $e) {
            Log::error('LocationError:' . json_encode($e));
        }
        return $result;
    }

    public static function fillObjectWidthArray($object, array $data)
    {
        $has = get_object_vars($object);
        foreach ($has as $name => $oldValue) {
            if (array_key_exists($name, $data)) {
                $object->$name = $data[ $name ];
            }
        }
    }

    public static function getArrayFromArray(array $sourceArray, $fields = null)
    {
        if (!$fields) {
            return (new \ArrayObject($sourceArray))->getArrayCopy();
        }else{
            $arr = [];
            foreach ($fields as $field) {
                if (array_key_exists($field, $sourceArray)) {
                    $arr[ $field ] = $sourceArray[ $field ];
                }
            }
            return $arr;
        }
    }


    public static function YEEPAY_COLUMNS(){
        return [
            'member_type'=>'memberType',
            'active_status'=>'activeStatus',
            'bank_card_number'=>'cardNo',
            'bank_card_status'=>'cardStatus',
            'auto_tender' => 'autoTender',
            'balance' => 'availableAmount',
            'frozen' => 'freezeAmount',
            'project_id'=>'tenderOrderNo',
        ];
    }

    public static function sendGet($url)
    {
        //创建一个新的cURL资源
        $curl = curl_init();
        //设置curl传输选项
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        //设置URL
        curl_setopt($curl, CURLOPT_URL, $url);
        //抓取URL并把它传递给浏览器
        return curl_exec($curl);
    }

    /**
     * @param $model
     * @param array $data
     * @param array $forceUpdateAttr //
     * @return mixed
     */
    public static function fillModelWithArray($model, array $data, $forceUpdateAttr = null)
    {
        $arr = $model->attributesToArray();
        foreach($arr as $key => $value)
        {
            $dataKey = $key;
            if (array_key_exists($key, Utils::YEEPAY_COLUMNS())) {
                $dataKey = Utils::YEEPAY_COLUMNS()[ $key ];
            }
            if (array_key_exists($dataKey, $data)) {
                switch (gettype($value)) {
                    case 'integer':
                        $result = (int)$data[ $dataKey ];
                        break;
                    case 'double':
                        $result = (double)$data[ $dataKey ];
                        break;
                    default:
                        $result = $data[ $dataKey ];
                }
                $model->$key = $result;
            }elseif (!empty($value) && $forceUpdateAttr && false!==array_search($key, $forceUpdateAttr)){
                $model->$key = null;
            }
        }
        return $model->update();
    }

    public static function hashidEncode($id)
    {
        $hashids = new Hashids(config('nxd.hashid_salt'), config('nxd.hashid_len'), 'abcdefghijkmnpqrstuvwxyz1234567890');
        return $hashids->encode($id);
    }

    public static function hashidDecode($code)
    {
        $hashids = new Hashids(config('nxd.hashid_salt'), config('nxd.hashid_len'), 'abcdefghijkmnpqrstuvwxyz1234567890');
        $arr = $hashids->decode($code);
        if (sizeof($arr) == 1) {
            return $arr[0];
        }else{
            return $arr;
        }
    }

    public static function snEncode($id)
    {
        $hashids = new Hashids(config('nxd.hashid_salt'), config('nxd.hashid_len'), 'ABCDEFG123567890');
        return $hashids->encode($id);
    }

    public static function snDecode($code)
    {
        $hashids = new Hashids(config('nxd.hashid_salt'), config('nxd.hashid_len'), 'ABCDEFG123567890');
        $arr = $hashids->decode($code);
        if (sizeof($arr) == 1) {
            return $arr[0];
        }else{
            return $arr;
        }
    }

    /**
     * 判断有效期是否合法
     * @param $date 有效截止日期
     * @return bool
     */
    public static function isDateValidate($date)
    {
        $now = Carbon::today();
        $date = Utils::getCarbonFromString($date);
        return $now->lte($date);
    }

    /**
     * 格式化时间
     * @param $string
     * @return static
     */
    public static function getCarbonFromString($string, $forceDate=false)
    {
        $rule = 'Y-m-d H:i:s';
        if ($forceDate && strpos($string, ':')) {
            $string = self::getDateStringFromDateTimeString($string);
        }
        $string = strpos($string, ':') ? $string : ($string.' 00:00:00');
        return Carbon::createFromFormat($rule, $string);
    }

    /**
     * 约束为'Y-M-D'格式
     * @param $string
     * @return string
     */
    public static function getDateStringFromDateTimeString($string)
    {
        return Carbon::createFromFormat('Y-m-d H:i:s',$string)->toDateString();
    }

    /**
     * 加密字符串
     * @param $string 传入字符
     * @param int $starNum 星星替代字符
     * @param int $showNum 首尾显示字符数
     */
    public static function encodeString($string,$showNum=2,$starString='**',$hideLast=false)
    {
        return $hideLast ? iconv_substr($string,0,$showNum).$starString : iconv_substr($string,0,$showNum).$starString.iconv_substr($string,-$showNum,$showNum);
    }

    public static function encodeMobile($mobile)
    {
        return substr_replace($mobile, '****', 3, 4);
    }

    /**
     * 截取身份证号码
     * @param $str
     * @param $start
     * @param $end
     * @param string $string
     * @return string
     */
    public static function encodeCardNumber($str , $start , $end , $string = "****"){
       return iconv_substr($str , 0 , $start) .$string. iconv_substr($str, -$end);
    }
    /**
     * 生成当前时间+随机码的数字，用作测试ID
     * @return string
     */
    public static function generateId()
    {
        return (date('his')+0).rand(100, 999);
    }


    /**
     * 得到字符串byte长度
     * @param $string
     * @return int
     */
    public static function getByteLenOfString($string){
        return mb_strlen($string,'iso-8859-1');
    }

    /**
     * 根据字符串长度加后缀
     * @param $string
     * @return int
     */
    public static function getShortStringWithLen($string){
        return (mb_strlen($string)>16)?(mb_substr($string,0,16)."..."):$string;
    }

    public static function shortUrl($long_url)
    {
        return App::make('wechat.url')->short($long_url);
    }

    /**
     * 判断有几位小数
     * @param $long_url
     * @return mixed
     */
    public static function numberLen($number)
    {
        $count = 0;
        $temp = explode ( '.', $number);

        if (sizeof ( $temp ) > 1) {
            $decimal = end ( $temp );
            $count = strlen ( $decimal );
        }
        return $count;
    }

    /**
     * 判断整数部分有几位
     * @param $long_url
     * @return mixed
     */
    public static function numberIntLen($number)
    {
        $count = 0;
        $temp = explode ( '.', $number);

        if (sizeof ( $temp ) > 1) {
            $decimal = $temp[0];
            $count = strlen ( $decimal );
        }
        return $count;
    }

    public static function isMobile($string)
    {
        return preg_match("/^1[0-9]{2}[0-9]{8}$|15[0189]{1}[0-9]{8}$|189[0-9]{8}$/", $string);
    }
    public static function smsWords($word){
        return str_replace('信贷',"贷",$word);
    }

    public static function getYeepayBanks() {
        return array(
            'BOCO' => '交通银行',
            'CEB' => '光大银行',
            'SPDB' => '上海浦东发展银行',
            'ABC' => '农业银行',
            'ECITIC' => '中信银行',
            'CCB' => '建设银行',
            'CMBC' => '民生银行',
            'SDB' => '平安银行',
            'POST' => '中国邮政储蓄',
            'CMBCHINA' => '招商银行',
            'CIB' => '兴业银行',
            'ICBC' => '中国工商银行',
            'BOC' => '中国银行',
            'BCCB' => '北京银行',
            'GDB' => '广发银行',
            'HX' => '华夏银行',
            'XAYH' => '西安市商业银行',
            'SHYH' => '上海银行',
            'TJYH' => '天津市商业银行',
            'SZNCSYYH' => '深圳农村商业银行',
            'BJNCSYYH' => '北京农商银行',
            'HZYH' => '杭州市商业银行',
            'KLYH' => '昆仑银行',
            'ZHENGZYH' => '郑州银行',
            'WZYH' => '温州银行',
            'HKYH' => '汉口银行',
            'NJYH' => '南京银行',
            'XMYH' => '厦门银行',
            'NCYH' => '南昌银行',
            'JISYH' => '江苏银行',
            'HKBEA' => '东亚银行(中国)',
            'CDYH' => '成都银行',
            'NBYH' => '宁波银行',
            'CSYH' => '长沙银行',
            'HBYH' => '河北银行',
            'GUAZYH' => '广州银行',
            'PSBC' => '中国邮政储蓄'
        );
    }
    public static function getAgeByID($id){

        //过了这年的生日才算多了1周岁
        if (empty($id)) {
            return '';
        }
        $date = strtotime(substr($id, 6, 8));
        //获得出生年月日的时间戳
        $today = strtotime('today');
        //获得今日的时间戳
        $diff = floor(($today - $date) / 86400 / 365);
        //得到两个日期相差的大体年数

        //strtotime加上这个年数后得到那日的时间戳后与今日的时间戳相比
        $age = strtotime(substr($id, 6, 8) . ' +' . $diff . 'years') > $today ? ($diff + 1) : $diff;

        return  (int)$age;
    }

    public static function getBirthdayFromIdCard($IDCard)
    {
        $result['error'] = 0;//0：未知错误，1：身份证格式错误，2：无错误
        $result['flag'] = '';//0标示成年，1标示未成年
        $result['birthday'] = '';//生日，格式如：2012-11-15
        $birthday = '0000-00-00';
        $arr = [];
        preg_match("/^[1-9]([0-9a-zA-Z]{17}|[0-9a-zA-Z]{14})$/", $IDCard, $arr);
        if (empty($arr)) {
            return $birthday;
        } else {
            if (strlen($IDCard) == 18) {
                $tyear = intval(substr($IDCard, 6, 4));
                $tmonth = intval(substr($IDCard, 10, 2));
                $tday = intval(substr($IDCard, 12, 2));
                if ($tyear > date("Y") || $tyear < (date("Y") - 100)) {
                    $flag = 0;
                } elseif ($tmonth < 0 || $tmonth > 12) {
                    $flag = 0;
                } elseif ($tday < 0 || $tday > 31) {
                    $flag = 0;
                } else {
                    $birthday = $tyear . "-" . $tmonth . "-" . $tday . " 00:00:00";
                    if ((time() - mktime(0, 0, 0, $tmonth, $tday, $tyear)) > 18 * 365 * 24 * 60 * 60) {
                        $flag = 0;
                    } else {
                        $flag = 1;
                    }
                }
            } elseif (strlen($IDCard) == 15) {
                $tyear = intval("19" . substr($IDCard, 6, 2));
                $tmonth = intval(substr($IDCard, 8, 2));
                $tday = intval(substr($IDCard, 10, 2));
                if ($tyear > date("Y") || $tyear < (date("Y") - 100)) {
                    $flag = 0;
                } elseif ($tmonth < 0 || $tmonth > 12) {
                    $flag = 0;
                } elseif ($tday < 0 || $tday > 31) {
                    $flag = 0;
                } else {
                    $birthday = $tyear . "-" . $tmonth . "-" . $tday;
                    if ((time() - mktime(0, 0, 0, $tmonth, $tday, $tyear)) > 18 * 365 * 24 * 60 * 60) {
                        $flag = 0;
                    } else {
                        $flag = 1;
                    }
                }
            }
        }
        return $birthday;
    }

}