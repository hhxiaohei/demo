<?php
/**
 * User: Mani Wang
 * Date: 10/28/16
 * Time: 4:06 PM
 * Email: mani@forone.co
 */

namespace App\Utils;


class Apix
{

    const HOST = 'http://e.apix.cn/apixanalysis/';


    /*
     * ****** 征信接口 ******
     */

    /**
     * 征信注册验证码
     * @param $id_number 身份证号
     * @return mixed
     */
    public static function creditSignUpCapcha($id_number)
    {
        $url = self::HOST . 'pbccrc/grant/credit/pbc/reg/capcha?cardno=' . $id_number;
        return self::sendGet($url, env('APIX_CREDIT_KEY'));
    }

    /**
     * 征信注册实名验证
     * @param $id_number
     * @param $name
     * @param $capcha
     * @return mixed
     */
    public static function creditSignUpCheck($id_number, $name, $capcha)
    {
        $url = self::HOST . 'pbccrc/grant/credit/pbc/reg/check?cardno=' . $id_number . '&name=' . $name . '&capcha=' . $capcha;
        return self::sendGet($url, env('APIX_CREDIT_KEY'));
    }

    /**
     * 注册短信验证码
     * @param $id_number
     * @param $mobile
     * @return mixed
     */
    public static function creditSignUpSms($id_number, $mobile)
    {
        $url = self::HOST . 'pbccrc/grant/credit/pbc/reg/smscode?cardno=' . $id_number . '&phone_no=' . $mobile;
        return self::sendGet($url, env('APIX_CREDIT_KEY'));
    }

    /**
     * 注册账号
     * @param $id_number
     * @param $mobile
     * @param $login_name
     * @param $passwd
     * @param $sms_code
     * @param $email
     */
    public static function creditSignUp($id_number, $mobile, $login_name, $passwd, $sms_code, $email)
    {
        $url = self::HOST . 'pbccrc/grant/credit/pbc/reg/userinfo?cardno=' . $id_number . '&phone_no=' . $mobile . '&login_name=' . $login_name . '&passwd=' . $passwd . '&sms_code=' . $sms_code . '&email=' . $email;
        return self::sendGet($url, env('APIX_CREDIT_KEY'));
    }

    /**
     * 获取登录验证码
     * @param $login_name
     */
    public static function creditSignInCapcha($login_name)
    {
        $url = self::HOST . 'pbccrc/grant/credit/pbc/capcha?login_name=' . $login_name;
        return self::sendGet($url, env('APIX_CREDIT_KEY'));
    }

    /**
     * 征信登录
     * @param $login_name
     * @param $passwd
     * @param $capcha
     * @param $idcode
     * @return mixed
     */
    public static function creditSignIn($login_name, $passwd, $capcha, $idcode)
    {
        $url = self::HOST . 'pbccrc/grant/credit/pbc/login?login_name=' . $login_name . '&passwd=' . $passwd . '&capcha=' . $capcha . '&idcode=' . $idcode;
        return self::sendGet($url, env('APIX_CREDIT_KEY'));
    }

    /**
     * 快捷查询短信
     * @param $login_name
     * @return mixed
     */
    public static function creditQuerySmsCode($login_name)
    {
        $url = self::HOST . 'pbccrc/grant/credit/pbc/express/code?login_name=' . $login_name;
        return self::sendGet($url, env('APIX_CREDIT_KEY'));
    }

    /**
     * 提交快捷查询验证码
     * @param $login_name
     * @param $sms_code
     * @return mixed
     */
    public static function creditQueryReport($login_name, $sms_code)
    {
        $url = self::HOST . 'pbccrc/grant/credit/pbc/express/report?login_name=' . $login_name . '&sms_code=' . $sms_code;
        return self::sendGet($url, env('APIX_CREDIT_KEY'));
    }

    /**
     * 安全问题
     * @param $login_name
     * @return mixed
     */
    public static function creditQuestions($login_name)
    {
        $url = self::HOST . 'pbccrc/grant/credit/pbc/questions?login_name=' . $login_name;
        return self::sendGet($url, env('APIX_CREDIT_KEY'));
    }

    /**
     * 提交答案
     * @param $login_name
     * @param $q_first
     * @param $q_second
     * @param $q_third
     * @param $q_fouth
     * @param $q_fivth
     * @return mixed
     */
    public static function creditAnswer($login_name, $q_first, $q_second, $q_third, $q_fouth, $q_fivth)
    {
        $url = self::HOST . 'pbccrc/grant/credit/pbc/answer?login_name=' . $login_name . '&q_first=' . $q_first . '&q_second=' . $q_second . '&q_third=' . $q_third . '&q_fouth=' . $q_fouth . '&q_fivth=' . $q_fivth;
        return self::sendGet($url, env('APIX_CREDIT_KEY'));
    }

    /**
     * 身份验证码校验接口
     * @param $login_name
     * @param $id_code
     * @return mixed
     */
    public static function creditIdCheck($login_name, $id_code)
    {
        $url = self::HOST . 'pbccrc/grant/credit/pbc/idcheck?login_name=' . $login_name . '&id_code=' . $id_code;
        return self::sendGet($url, env('APIX_CREDIT_KEY'));
    }

    /**
     * 征信报告数据查询
     * @param $query_code
     * @return mixed
     */
    public static function creditDataQuery($query_code)
    {
        $url = self::HOST . 'pbccrc/retrieve/credit/data/query?query_code=' . $query_code;
        return self::sendGet($url, env('APIX_CREDIT_KEY'));
    }

    /**
     * 征信报告找回用户名验证码
     * @param $id_number
     * @return mixed
     */
    public static function creditRetrieveUsernameCapcha($id_number)
    {
        $url = self::HOST . 'pbccrc/grant/credit/pbc/fname/capcha?card_no=' . $id_number;
        return self::sendGet($url, env('APIX_CREDIT_KEY'));
    }

    /**
     * 找回用户名
     * @param $id_number
     * @param $name
     * @param $capcha
     * @return mixed
     */
    public static function creditRetrieveUsername($id_number, $name, $capcha)
    {
        $url = self::HOST . 'pbccrc/grant/credit/pbc/fname/verify?card_no=' . $id_number . '&name=' . $name . '&capcha=' . $capcha;
        return self::sendGet($url, env('APIX_CREDIT_KEY'));
    }

    /*
     * ****** 手机接口 ******
     */

    /**
     * @param        $mobile 手机号
     * @param string $name 姓名选填
     * @param string $cert_no 身份证号选填
     * @param string $contact_list 字符串示例:“父亲:张三:18512345678,同事:陈文:13569784512”每个字段需要有 “关系:姓名:号码”三个为一组,每项由英文逗号拼接,结尾无需符号。提交的联系人个数要求在 1-20 个以 内,前三个为紧急联系人。
     * @return \Illuminate\Routing\Route
     */
    public static function mobileCapcha($mobile, $name = '', $cert_no = '', $contact_list = '')
    {
        $url = self::HOST . 'mobile/yys/phone/capcha?phone_no=' . $mobile . '&name=' . $name . '&cert_no=' . $cert_no . '&contact_list=' . $contact_list;

        return self::sendGet($url, env('APIX_MOBILE_KEY'));
    }

    /**
     * @param        $mobile 手机号
     * @return \Illuminate\Routing\Route
     */
    public static function mobileCapchaAuthorize($mobile)
    {
        $url = self::HOST . 'mobile/yys/phone/smsCode/getSms?phone_no=' . $mobile;

        return self::sendGet($url, env('APIX_MOBILE_KEY'));
    }

    /**
     * @param $token 前面步骤获取的token
     * @return mixed
     */
    public static function mobileAnalyzedFile($token)
    {
        $url = self::HOST . 'mobile/retrieve/phone/data/analyzed?query_code=' . $token;

        return self::sendGet($url, env('APIX_MOBILE_KEY'));
    }

    /**
     * @param $token 前面步骤获取的token
     * @return mixed
     */
    public static function mobileAnalyzedData($token)
    {
        $url = self::HOST . 'mobile/retrieve/phone/data/query?query_code=' . $token;

        return self::sendGet($url, env('APIX_MOBILE_KEY'));
    }


    /**
     * @param        $mobile 手机号
     * @param        $passwd 服务密码
     * @param        $capcha 短信验证码
     * @param string $callback 获取授权信息后回调通知接口
     */
    public static function mobileLogin($mobile, $passwd, $capcha, $callback = '')
    {
        $url = self::HOST . 'mobile/yys/phone/login?phone_no=' . $mobile . '&passwd=' . $passwd . '&capcha=' . $capcha . '&callback=' . $callback;

        return self::sendGet($url, env('APIX_MOBILE_KEY'));
    }

    /**
     * @param        $mobile 手机号
     * @param        $smsCode 短信验证码
     * @param string $name 姓名
     * @param string $id_no 身份证号
     * @param string $capcha 图形验证码
     * @return mixed
     */
    public static function mobileCapchaVerify($mobile, $smsCode, $name = '', $id_no = '', $capcha = '')
    {
        $url = self::HOST . 'mobile/yys/phone/smsCode/verify?phone_no=' . $mobile . '&sms_code=' . $smsCode . '&name=' . $name . '&cert_no=' . $id_no . '&capcha=' . $capcha;

        return self::sendGet($url, env('APIX_MOBILE_KEY'));
    }

    /**
     * 解析身份证号
     * @param $id_number
     */
    public static function idCardInfo($id_number)
    {
        $url = 'http://a.apix.cn/tongyu/idcardinfo/id?id=' . $id_number;
        $result = self::sendGet($url, 'f021122214fa436265fcdf73085212d0');
        $result = json_decode($result);
        $data = '';
        if ($result->error_code == '0') {
            $data = $result->data;
            $data->gender = $data->gender == 'M' ? '男' : '女';
        }
        return $data;
    }

    /**
     * 获取手机号运营商和地址信息
     * @param $id_number
     */
    public static function mobileApixInfo($mobile)
    {
        $url = 'http://a.apix.cn/apixlife/phone/phone?phone=' . $mobile;
        $result = self::sendGet($url, '73f10128543d415849f18bc5002cdf73');
        $result = json_decode($result);
        if ($result->error_code == '0') {
            $data = $result->data;

            return ['city' => $data->province . $data->city, 'remark' => $data->operator];
        }else{
            return ['city' => '', 'remark' => ''];
        }

    }

    /**
     * 获取手机号的所在地和备注信息
     * @param $mobile
     * @return array
     */
    public static function mobileInfo($mobile)
    {
        $url = 'http://www.ip.cn/db.php?num=' . $mobile;
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:text/html; charset=UTF-8'));
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('User-Agent:Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_5) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/54.0.2840.71 Safari/537.36'));
        curl_setopt($curl, CURLOPT_URL, $url);
        $result = curl_exec($curl);
        $re = '/(?<=所在城市:).*?(?=\<br)/m';
        preg_match_all($re, $result, $city);
        if (sizeof($city[0])) {
            $city = $city[0][0];
        }else{
            $city = '';
        }
        $re = '/(?<=<\/code>：).*?(?=（仅供参考）)/m';
        preg_match_all($re, $result, $remark);
        if (sizeof($remark[0])) {
            $remark = $remark[0][0];
        }else{
            $remark = '';
        }
        return ['city' => $city, 'remark' => $remark];
    }

//    public static function sendPost($url, $data)
//    {
//        $curl = curl_init();
//        curl_setopt($curl, CURLOPT_HTTPHEADER, array('apix-key:' . env('APIX_KEY')));
//        curl_setopt($curl, CURLOPT_HEADER, 0);
//        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
//        curl_setopt($curl, CURLOPT_URL, $url);
//        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
//
//        return curl_exec($curl);
//    }

    public static function sendGet($url, $key = '')
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('apix-key:' . ($key ? $key : env('APIX_KEY'))));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_URL, $url);

        \Log::info('ApixGet:' . $url);

        return curl_exec($curl);
    }
}