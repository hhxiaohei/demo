<?php

namespace App\Utils;


use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserUtil {

    /**
     * 生成token
     * @param $user
     */
    public static function generateApiToken($user)
    {
        $token = JWTAuth::fromUser($user);
        $user->token = $token;
        RedisUtil::set($user->mobile, $token, 60 * 60 * 24 * 30);
    }

    /**
     * 更新token
     * @param $mobile
     * @return mixed
     */
    public static function refreshApiToken($mobile)
    {
        $user = User::where('mobile', $mobile)->first(['id', 'name', 'invitation_code']);
        $token = JWTAuth::fromUser($user);
        RedisUtil::set($mobile, $token, 60 * 60 * 24 * 30);
        return $token;
    }
}