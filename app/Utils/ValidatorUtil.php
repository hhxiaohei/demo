<?php namespace App\Utils;


class ValidatorUtil {

    const per_page = "integer|min:1";
    const page = "integer|min:1";
    const pagination = ["per_page"=>self::per_page,"page"=>self::page];
    const id = ["id"=>"required|id"];


    const mobileRequiredRule = "required|sms|exists:users,mobile";

    const mobileRequiredWord =  "手机号不存在";
    const mobileSmSWord =  "请在:time秒后再进行请求";

    const FindPasswordFirstSchema = [
        "mobile" => self::mobileRequiredRule,
        "pic_code" => "required|captcha"
    ];

    const FindPasswordFirstWords = [
        "mobile.exists" => self::mobileRequiredWord,
        "mobile.sms" => self::mobileSmSWord,
        "pic_code.captcha" => "图形验证码错误"
    ];


    const FindPasswordWords = [
        'code.required' => '验证码不能为空',
        'code'=>"验证码错误",
        'password.required' => '新密码不能为空',
        'password.password' => '密码格式不正确',
        'confirm_password.required' => '再次输入密码不能为空',
        'digits' => '验证码必须为6位数字',
        'same' => '两次输入密码必须一致',
        'min' => '密码不能低于6位',
    ];

    const FindPassWordSchema = [
        'code' => 'required|digits:6',
        'password' => 'required|password|same:confirm_password',
        'confirm_password' => 'required|password|same:password',
    ];


}