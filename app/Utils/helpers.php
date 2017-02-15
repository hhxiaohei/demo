<?php

if (!function_exists('show_img')) {
    //显示图片
    function show_img($img, $default = '')
    {
        $img_url = '';
        if (!$img) {
            //地址不存在
            if (strpos($default, 'http://') === 0) {
                //默认地址为绝对地址
                $img_url = $default;
            } elseif (strpos($default, '/') === false) {
                //默认地址为七牛地址
                $img_url = get_qiniu_url() . $default;
            } else {
                //默认地址为相对地址
                $img_url = URL::to($default);
            }
        } elseif (strpos($img, 'http://') === 0) {
            //绝对地址
            $img_url = $img;
        } else {
            if (strpos($img, '/') === false) {
                //七牛地址
                $img_url = get_qiniu_url() . $img;
            } else {
                //相对地址
                $img_url = URL::to($img);
            }
        }

        return $img_url;
    }
}

if (!function_exists('get_qiniu_url')) {
    //获取七牛文件访问地址
    function get_qiniu_url()
    {
        $config = Config::get('nxd');

        return 'http://' . $config['bucket_onarim'] . '.' . $config['qi_niu_host'] . '/';
    }
}

if (!function_exists('website_view')) {
    function website_view()
    {
        $args = func_get_args();
        if (!empty($args[0])) {
            $args[0] = 'website.' . $args[0];
        }

        return call_user_func_array('view', $args);
    }
}

if (!function_exists('admin_view')) {
    function admin_view()
    {
        $args = func_get_args();
        if (!empty($args[0])) {
            $args[0] = 'admin.' . $args[0];
        }

        return call_user_func_array('view', $args);
    }
}

if (!function_exists('wechat_view')) {
    function wechat_view()
    {
        $args = func_get_args();
        if (!empty($args[0])) {
            $args[0] = 'wechat.' . $args[0];
        }

        $share = [
            'title' => '宁夏贷',
            'desc' => '宁夏贷 国资背景服务三农的创新金融平台',
            'imgUrl' => 'http://cdn.nxdai.com/logo.png',
            'link' => URL::full(),
            'share_link' => URL::full(),
            'titleOfTimeline' => '宁夏贷 国资背景服务三农的创新金融平台'
        ];

        if (in_wechat()) {
            if (sizeof($args) > 1 && !array_key_exists('share', $args[1])) {
                $args[1]['share'] = $share;
            } else if (sizeof($args) == 1) {
                array_push($args, ['share' => $share]);
            }
        }

        return call_user_func_array('view', $args);
    }
}

if (!function_exists('in_wechat')) {
    function in_wechat()
    {
        return \Jenssegers\Agent\Facades\Agent::match('MicroMessenger');
    }
}

if (!function_exists('wechat_view_or_json')) {
    function wechat_view_or_json($viewName, $viewData, $jsonData, $condition = "UNDEFINED")
    {
        if ($condition == "UNDEFINED") {
            $condition = \Illuminate\Support\Facades\Input::get("page");
        }
        if ($condition) {
            return response()->json($jsonData);
        } else {
            return call_user_func_array('wechat_view', array($viewName, $viewData));
        }
    }
}
if (!function_exists('captcha_need')) {
    function captcha_need()
    {
        return \Illuminate\Support\Facades\Session::get("captcha:need");
    }
}

// 时间显示，只保留两位数
if (!function_exists('friendly_duration')) {
    function friendly_duration($seconds)
    {
        $seconds = ($seconds < 0) ? 0 : $seconds;
        if ($seconds < 3600) {// one hour
            return sprintf("%d分%d秒", floor($seconds / 60), $seconds % 60);
        } else {
            if ($seconds < 3600 * 24) { // one day
                return sprintf("%d时%d分", floor($seconds / 3600), floor(($seconds % 3600) / 60));
            }
        }

        return sprintf("%d天%d时", floor($seconds / (3600 * 24)), floor(($seconds % (3600 * 24)) / 3600));
    }
}

if (!function_exists('parse_config')) {
    function parse_config($configString)
    {
        $config = [];
        if ($configString) {
            $arr1 = explode('|', $config);
            foreach ($arr1 as $value) {
                $temp = explode('=', $value);
                if (sizeof($temp) == 1) {
                    $config[$value] = '';
                } else {
                    $tempArray = explode(' ', $temp[1]);
                    if (sizeof($tempArray) == 1) {
                        $config[$temp[0]] = $temp[1];
                    } else {
                        $arr = [];
                        foreach ($tempArray as $item) {
                            array_push($arr, ['value' => $item, 'label' => $item]);
                        }
                        $config[$temp[0]] = $arr;
                    }
                }
            }
        }

        return $config;
    }
}

// 加密
if (!function_exists('hashid_encode')) {
    function hashid_encode($id)
    {
        return \App\Utils\Utils::hashidEncode($id);
    }
}

// 解密
if (!function_exists('hashid_decode')) {
    function hashid_decode($code)
    {
        return \App\Utils\Utils::hashidDecode($code);
    }
}
// 解密
if (!function_exists('select_label_trans')) {
    function select_label_trans($data)
    {
        $res = [];
        foreach($data as $k=>$v)
        {
            array_push($res,["label"=>$v,"value"=>$k]);
        }
        return $res;
    }
}
// 配置
if (!function_exists('debug_assets')) {
    function debug_assets($path,$path2=null)
    {
//        return config('nxd.live_img_url').$path;
        $path2 =  $path2?$path2:$path;
        return config('app.debug') ? asset($path) : (config('nxd.live_img_url').$path2);
    }
}
// 是不是导入标
if (!function_exists('is_import')) {
    function is_import($id)
    {
        return array_search($id,explode(",",env("IMPORT_PRJECT_ID")))!==false;
//        return $id <= env("IMPORT_PRJECT_ID",300);
    }
}
if (!function_exists('number2cn')) {
    /**
     * 数字转中文
     * 数字在999999999999以下
     * 数字够大的时候小数点后会取整,奇怪了
     * @param string $number 无序数组
     * @return string $str 有序数组
     */
    function number2cn($number)
    {
        if (!is_numeric($number)) {
            return "非数字";
        } elseif ($number > 999999999999 || $number < -999999999999) {
            return "数字超出范围";
        }
        $p = explode(".", $number);
        $cn_arr = array("零", "壹", "贰", "叁", "肆", "伍", "陆", "柒", "捌", "玖");
        $p_arr = array("", "拾", "佰", "仟");
        $unit_arr = array("", "万", "亿");
        $str = "";
        $zero = false;//零标志
        $unit = "";//单位
        //处理小数点后数字
        if (isset($p[1])) {//小数点后有数字存在
            $dot = "點";
            $l = strlen($p[1]);
            for ($i = 0; $i < $l; $i++) {
                $dot .= $cn_arr[$p[1]{$i}];
            }
        } else {
            $dot = "";
        }
        $number = $p[0];    //小数点前数字
        $l = strlen($number);//数字位数
        for ($i = $l - 1, $j = 0; $i >= 0; $i--, $j++) {
            $pos = $number{$i};
            if ($pos == "-") {
                $str = "負" . $str;
                break;
            }
            if ($j % 4 == 0) {
                $unit = $unit_arr[$j / 4];
            }
            if ($pos == '0') {
                $zero = true;
                continue;
            }
            if ($zero) {
                if ($str != "") {
                    $str = $cn_arr[$pos] . $p_arr[$j % 4] . $unit . $cn_arr[0] . $str;
                } else {
                    $str = $cn_arr[$pos] . $p_arr[$j % 4] . $unit;
                }
                $zero = false;
            } else {
                $str = $cn_arr[$pos] . $p_arr[$j % 4] . $unit . $str;
            }
            $unit = "";
        }
        return $str . $dot;
    }

    if (!function_exists('ds')) {

        /**
         * Dump the last sql.
         *
         * @param  mixed
         * @return string
         */
        function ds()
        {
            $sql = DB::getQueryLog();
            $query = end($sql);
            dump($query['query']);
        }
    }
}
