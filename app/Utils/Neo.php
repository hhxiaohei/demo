<?php
/**
 * Created by PhpStorm.
 * User: chita
 * Date: 16/11/8
 * Time: 下午5:45
 */

namespace App\Utils;


use App\Models\NeoLog;
use App\Models\UserDetail;
use Carbon\Carbon;
use EasyWeChat\Support\Arr;

/**
 * Class Neo 存管系统
 * @package App\Utils
 */
class Neo
{

    private $deviceWay = [
        'ios'     => 'MOBILE',
        'android' => 'MOBILE',
        'web'     => 'PC',
        'wap'     => 'MOBILE',
    ];

    /**
     * @param        $realName 真实姓名
     * @param        $user_id 用户id
     * @param        $id_number 身份证号码
     * @param        $mobile 手机号
     * @param        null $bankcardNo 银行卡号
     * @param        $source 来源
     * @param        string $userIdentity 用户身份,INVESTOR 表示投资人, BORROWERS 表示借款人
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */

    public function personalRegister(
        $realName,
        $user_id,
        $id_number,
        $mobile,
        $bankcardNo = null,
        $userIdentity = NEO_INVESTOR,
        $source = 'web'
    )
    {
        $array = [
            'platformUserNo' => hashid_encode($user_id),
            'realName'       => $realName,
            'idCardType'     => 'PRC_ID',
            'userRole'       => NEO_USER_ROLE_NORMAL,
            'idCardNo'       => $id_number,
            'mobile'         => $mobile,
//            'userDevice'     => $this->deviceWay[$source],
            'source'         => $source,
            'bankcardNo'     => $bankcardNo,
            'userIdentity'   => $userIdentity,
            'checkType'      => 'LIMIT',
            'callbackUrl'    => route('neo.register_callback'),

        ];

        return $this->sendGateRequest(NEO_PERSONAL_REGISTER, $array);
    }

    /**
     * @param        $user_id 用户id
     * @param        $enterpriseName 企业名称
     * @param        $bankLicense 开户银行许可号
     * @param        $legal 法人姓名
     * @param        $legalIdCardNo 法人身份证号
     * @param        $contact 企业联系人
     * @param        $contactPhone 联系人手机号
     * @param        $userRole 用户角色
     * @param        $bankcardNo 企业对公账户显示后四位
     * @param        $bankcode 见银行编码
     * @param string $orgNo 组织机构代码
     * @param string $businessLicense 营业执照编号
     * @param string $taxNo 税务登记号
     * @param string $unifiedCode 统一社会信用代码(可替代三证)
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function enterpriseRegister(
        $user_id,
        $enterpriseName,
        $bankLicense,
        $legal,
        $legalIdCardNo,
        $contact,
        $contactPhone,
        $userRole,
        $bankcardNo,
        $bankcode,
        $orgNo = '',
        $businessLicense = '',
        $taxNo = '',
        $unifiedCode = ''
    )
    {
        $array = [
            'platformUserNo'  => hashid_encode($user_id),
            'enterpriseName'  => $enterpriseName,
            'bankLicense'     => $bankLicense,
            'legal'           => $legal,
            'legalIdCardNo'   => $legalIdCardNo,
            'contact'         => $contact,
            'contactPhone'    => $contactPhone,
            'userRole'        => $userRole,
            'bankcardNo'      => $bankcardNo,
            'bankcode'        => $bankcode,
            'orgNo'           => $orgNo,
            'businessLicense' => $businessLicense,
            'taxNo'           => $taxNo,
            'unifiedCode'     => $unifiedCode,
            'callbackUrl'     => route('neo.enterprise_register_callback'),
        ];

        return $this->sendGateRequest(NEO_ENTERPRISE_REGISTER, $array);
    }

    /**
     * 查询用户信息
     * @param $userId 用户id
     * @return bool|string
     */
    public function queryUserInformation($userId)
    {
        return $this->sendQueryRequest(NEO_QUERY_USER_INFORMATION, ['platformUserNo' => hashid_encode($userId)]);
    }


    /**
     *
     * P2P 平台向网贷存管通发送标的信息
     * 业务规则
     * R1. 新创建标的状态为募集中
     * R2. 标的扩展信息参数格式各存管银行不同,详见WIKI
     * 否幂等
     * 直连
     * 无异步通知
     * @param $user_id int  借款方平台用户编号
     * @param $project_id int   标的号
     * @param $projectAmount int   标的金额
     * @param $projectName string  标的名称
     * @param $projectDescription string  标的描述
     * @param $projectType 【标的产品类型】
     * @param $annnualInterestRate float 年化利率 annnualInterestRate只能是0.00001这种格式,年华利率是9%的话就填0.09，但是这个只是纪录 ，我们不会真实的限制，不允许超过两位数 ,必须小于1
     * @param $repaymentWay 【还款方式】
     * @param $extend   标的扩展信息
     */
    public function establishProject(
        $user_id,
        $project_id,
        $projectAmount,
        $projectName,
        $projectDescription,
        $projectType,
        $annnualInterestRate,
        $repaymentWay,
        $extend = null
    )
    {
        return $this->sendDirectRequest(NEO_ESTABLISH_PROJECT, [
            "user_id"             => $user_id,
            "project_id"          => $project_id,
            "projectAmount"       => $projectAmount,
            "projectName"         => $projectName,
            "projectDescription"  => $projectDescription,
            "projectType"         => $projectType,
            "annnualInterestRate" => $annnualInterestRate,
            "repaymentWay"        => $repaymentWay,
            "extend"              => $extend,
        ]);

    }


    /**
     * 个人绑卡
     * @param $user_id 平台用户编号
     * @param $mobile 银行预留手机号
     * @param $bankcardNo 银行卡号
     * @param string $source
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function personalBindBankcard($user_id, $mobile, $bankcardNo, $source = "web")
    {
        $array = [
            "platformUserNo" => hashid_encode($user_id),
            "mobile"         => $mobile,
            "bankcardNo"     => $bankcardNo,
            "userDevice"     => $this->deviceWay[$source],
            "checkType"      => "LIMIT",
            "callbackUrl"    => action("NeoController@postBindBankCardCallback"),
        ];
        return $this->sendGateRequest(NEO_PERSONAL_BIND_BANKCARD, $array);
    }

    /**
     * 企业绑卡
     * @param $user_id 平台用户编号
     * @param $mobile  银行预留手机号
     * @param $bankcardNo 银行卡号
     * @param $bankcode 银行编码
     * @param string $source
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function enterpriseBindBankcard($user_id, $mobile, $bankcardNo, $bankcode, $source = "web")
    {
        $array = [
            "platformUserNo" => hashid_encode($user_id),
            "mobile"         => $mobile,
            "bankcardNo"     => $bankcardNo,
            "bankcode"       => $bankcode,
            "userDevice"     => $this->deviceWay[$source],
            "checkType"      => "LIMIT",
            "callbackUrl"    => action("NeoController@postBindBankCardCallback"),
        ];
        return $this->sendGateRequest(NEO_ENTERPRISE_BIND_BANKCARD, $array);
    }

    /**
     * 平台通过此接口变更标的状态
     * 标的状态变更顺序为募集中——》还款中——》已截标或募集中——》流标,标的状态不可逆向
     * R1.募集中状态允许用户预处理业务(投标)、授权预处理业务(投标)、取消投标、放款确认及债权转让
     * R2.还款中状态允许用户预处理业务(还款/代偿/债权转让)、还款确认、债权转让确认、平台预处理业务(代偿)
     * R3.已截标状态、流标状态不允许用户操作任何标的业务
     * 直连
     * @param $project_id string 50 标的号
     * @param $status 更新标的状态,见【标的状态】
     *
     */
    public function modifyProject($project_id, $status)
    {
        return $this->sendDirectRequest(NEO_MODIFY_PROJECT, [
            "projectNo" => hashid_encode($project_id),
            "status"    => $status
        ]);
    }

    /**
     * 用户充值
     * @param $user_id 平台用户编号
     * @param string $source
     * @param $amount 充值金额
     * @param $expectPayCompany 平台佣金
     * @param $rechargeWay 【支付方式】，支持网银、快捷支付
     * @param int $commission 偏好支付公司
     * @param int $expired 超过此时间即页面过期
     * @param string $callbackMode 快捷充值回调模式，如传入 DIRECT_CALLBACK，则订单支付不论成功、失败、 处理中均直接同步、异步通知商户;未传入订单仅在支付成功时通知商户;
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function recharge($user_id, $amount, $expectPayCompany, $rechargeWay, $bankcode, $extra_invest_status, $source = "web", $callbackMode = 'DIRECT_CALLBACK')
    {

        $expired = date('YmdHis', time() + 100);
        $array = [
            "platformUserNo"   => hashid_encode($user_id),
            "amount"           => $amount,
            //"commission"       => $commission,
            "expectPayCompany" => $expectPayCompany,
            "rechargeWay"      => $rechargeWay,
            "bankcode"         => $bankcode,
            "expired"          => $expired,
            "userDevice"       => $this->deviceWay[$source],
            "callbackMode"     => $callbackMode,
            "callbackUrl"      => route('neo.recharge_neo_callback'),
        ];

        if ($extra_invest_status != false) {
            $array['extra_invest_status'] = $extra_invest_status;
        }

        return $this->sendGateRequest(NEO_RECHARGE, $array);
    }

    /**
     * 提现
     * @param $user_id  平台用户编号
     * @param $amount 提现金额
     * @param $withdrawType 目前仅支持正常提现 NORMAL 正常提现，T+1 天到账
     * @param string $withdrawForm 提现类型，IMMEDIATE 为直接提现，CONFIRMED 为待确认提现，默认为直接提 现方式
     * @param int $expired 超过此时间即页面过期
     * @param int $commission 提现分佣
     * @param string $source
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response提现
     */
    public function withdraw($user_id, $amount, $withdrawType, $withdrawForm, $expired, $source = "web")
    {
        $array = [
            "platformUserNo" => hashid_encode($user_id),
            "amount"         => $amount,
            "withdrawType"   => $withdrawType,
            "withdrawForm"   => $withdrawForm,
            "expired"        => $expired,
            "userDevice"     => $this->deviceWay[$source],
            "callbackUrl"    => route('neo.withdraw_neo_callback'),
        ];

        $array['extra_withdraw_status'] = $withdrawForm;

        return $this->sendGateRequest(NEO_WITHDRAW, $array);
    }

    /**
     * 后台提现确认
     * @param $user_id
     * @param $preTransactionNo 待确认的提现请求id
     * @param string $source
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function confirmithdraw($request_no, $source = "web")
    {
        $array = [
            "preTransactionNo" => $request_no,
            "userDevice"       => $this->deviceWay[$source],
            "callbackUrl"      => route('neo.confirm_withdraw_neo_callback'),
        ];

        return $this->sendGateRequest(NEO_CONFIRM_WITHDRAW, $array);
    }

    /**
     * 后台提现取消
     * @param $user_id
     * @param $preTransactionNo 待确认的提现请求id
     * @param string $source
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function cancelWithdraw($request_no, $source = "web"){
        $array = [
            "preTransactionNo" => $request_no,
            "userDevice"       => $this->deviceWay[$source],
            "callbackUrl"      => route('neo.cancel_withdraw_neo_callback'),
        ];

        return $this->sendGateRequest(NEO_CANCEL_WITHDRAW, $array);
    }

    /**
     * 修改支付密码
     * $user_id 用户id
     */
    public function resetPassword($user_id, $source = "web")
    {
        $array = [
            "platformUserNo" => hashid_encode($user_id),
            "userDevice"     => $this->deviceWay[$source],
            "callbackUrl"    => route('neo.reset_password_callback'),
        ];
        return $this->sendGateRequest(NEO_RESET_PASSWORD, $array);
    }

    /**
     * 标的满标之后,平台通过该接口将投标预处理冻结的资金划转至借款人账户
     * R1. 标的状态必须为募集中
     * R2. 平台佣金将从借款人金额中扣除,借款人实际到账金额=放款金额-平台佣金
     * 直连  幂等
     * @param $project_id int 标的id
     * @param $commission float 平台佣金
     * @param $detail array 放款详细
     * <code>
     * [
     * "preTransactionNo"=>"", // 50 预处理业务流水号
     * "amount"=>"", // 放款金额,同一笔预处理冻结的金额允许多次放款,每次放一部分
     * "marketingAmount"=>"", // 用户使用平台营销款金额投标,要求与各类预处理的预备使用的红包金额一致
     * "customDefine"=>"", // 50 平台商户自定义参数,平台交易时传入的自定义参数
     * ]
     * </code>
     * @return array
     */
    public function confirmLoan($project_id, $commission, $detail)
    {
        return $this->sendDirectRequest(NEO_NEO_CONFIRM_LOAN, [
            "projectNo"  => hashid_encode($project_id),
            "commission" => $commission,
            "detail"     => $detail
        ]);
    }

    /**
     * @param $user_id
     * @param $bizType 根据业务的不同,需要传入不同的值,见【预处理业务类型】。
     * @param $amount 冻结金额
     * @param $expired 超过此时间即页面过期
     * @param $remark 备注
     * @param $project_id
     * @param $preMarketingAmount 预备使用的红包金额,只记录不冻结,仅限投标业务类型
     * @param $share 购买债转份额,业务类型为购买债权时,需要传此参数
     * @param $creditaleRequestNo 债权出让请求流水号,只有购买债权业务需填此参数
     * @param $source 来源
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function userPreTransaction(
        $user_id,
        $bizType,
        $amount,
        $expired,
        $project_id,
        $remark = null,
        $preMarketingAmount = null,
        $share = null,
        $creditaleRequestNo = null,
        $source = "web"
    )
    {
        return $this->sendGateRequest(USER_PRE_TRANSACTION, [
            "project_id"          => $project_id,
            "user_id"             => $user_id,
            "bizType"             => $bizType,
            "amount"              => $amount,
            "expired"             => $expired,
            "remark"              => $remark,
            "preMarketingAmount"  => $preMarketingAmount,
            "share"               => $share,
            "creditsaleRequestNo" => $creditaleRequestNo,
            "source"              => $source,
            "callbackUrl"         => route('neo.post_transaction_callback'),
        ]);
    }


    /*
     * ******
     * 公共方法
     * ******
     */


    /**
     * @param $req
     * @return string
     */
    public function sign($req)
    {
        $pem = "-----BEGIN RSA PRIVATE KEY-----\r\n" . chunk_split(config('nxd.bha_neo_private'),
                64) . "-----END RSA PRIVATE KEY-----";

        $sign = 'abc';
        openssl_sign($req, $sign, $pem);

        return base64_encode($sign);
    }

    /**
     * @param $body
     * @param $sign
     * @return bool
     */
    public function verify($body, $sign)
    {
        $pem = "-----BEGIN PUBLIC KEY-----\r\n" . chunk_split(config('nxd.bha_neo_public'),
                64) . '-----END PUBLIC KEY-----';

        $raw_sign = base64_decode($sign);

        $result = openssl_verify($body, $raw_sign, $pem);

        return $result == 1;
    }

    private function sendPostAutoSign($url, $serviceName, Array $req)
    {
        $col = collect($req)->reject(function ($item) {
            return is_null($item);
        });
        $map = [
            "user_id"    => "platformUserNo",
            "project_id" => "projectNo",
            "source"     => "userDevice"
        ];
        collect($req)->only(array_keys($map))->each(function ($item, $key) use ($col, $map) {
            $mKey = $map[$key];
            if (!$col->has($mKey) && !is_null($item)) {
                if ($key == "source") {
                    $col->put($mKey, $this->deviceWay[$item]);
                } else {
                    $col->put($mKey, hashid_encode($item));
                }

            }
        });

        $neoLog = NeoLog::create([
            "extra"        => $col->except(["platformUserNo", "userDevice", "callbackUrl"])->toArray(),
            "service_name" => $serviceName,
            "user_id"      => $col->get("user_id") ? $col->get("user_id") : hashid_decode($col->get("platformUserNo")),
            "project_id"   => $col->get("project_id") ? $col->get("project_id") : ($col->get("projectNo") ? hashid_decode($col->get("projectNo")) : null),
            "user_device"  => NeoLog::deviceValue($col->get("source"))
        ]);
        $col->put("requestNo", $neoLog->neoNo);
        $col->put("timestamp", isset($req["timestamp"]) ?: $neoLog->created_at->format('YmdHis'));
        $col = $col->except(array_keys($map))->except($col->keys()->filter(function ($key) {
            return starts_with($key, "extra");
        }));
        $reqJson = $col->toJson();
        $data = [
            "platformNo"  => env('NEO_PLATFORM_NO'),
            "serviceName" => $serviceName,
            "reqData"     => $reqJson,
            "sign"        => $this->sign($reqJson),
            "keySerial"   => "1"
        ];

        return $this->sendPost($url, $data);
    }

    /**
     * 检查 返回是否成功
     * @param $res
     * @return bool
     */
    public function checkResponse($res)
    {
        return $res && (Arr::get($res, "code") == "0");
    }


    /**
     * 查询接口
     * @param       $serviceName 接口名称
     * @param array $req 参数
     * @return bool|string
     */
    public function sendQueryRequest($serviceName, Array $req)
    {

        list($headers, $body) = $this->sendPostAutoSign(env('NEO_DIRECT_HOST'), $serviceName, $req);

        return !$this->verify($body, Arr::get($headers, "sign", "")) ? false : json_decode($body, true);
    }

    /**
     * 直连接口
     * @param       $serviceName 接口名称
     * @param array $req 参数
     * @return bool|string
     */
    public function sendDirectRequest($serviceName, Array $req)
    {
        list($headers, $body) = $this->sendPostAutoSign(env('NEO_DIRECT_HOST'), $serviceName, $req);

        return !$this->verify($body, Arr::get($headers, "sign", "")) ? false : json_decode($body, true);
    }

    /**
     * 网关接口
     * @param       $serviceName 接口名称
     * @param array $req 参数
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function sendGateRequest($serviceName, Array $req)
    {
        list($headers, $body) = $this->sendPostAutoSign(env('NEO_HOST'), $serviceName, $req);
        return response($body, 200, $headers);
    }

    /**
     * curl
     * @param $url
     * @param $data
     * @return mixed
     */
    private function sendPost($url, $data)
    {
        \Log::info('NeoRequest:' . json_encode($data));
        $headers = [];
        $key = '';
        $handleHeaderLine = function ($curl, $header_line) use (&$headers, &$sign, &$key) {
            $h = explode(':', $header_line, 2);

            if (isset($h[1])) {
                if (!isset($headers[$h[0]])) {
                    $headers[$h[0]] = trim($h[1]);
                } elseif (is_array($headers[$h[0]])) {
                    $headers[$h[0]] = array_merge($headers[$h[0]], array(trim($h[1])));
                } else {
                    $headers[$h[0]] = array_merge(array($headers[$h[0]]), array(trim($h[1])));
                }
                $key = $h[0];
            } else {
                if (substr($h[0], 0, 1) == "\t") {
                    $headers[$key] .= "\r\n\t" . trim($h[0]);
                } elseif (!$key) {
                    $headers[0] = trim($h[0]);
                }
            }

            return strlen($header_line);
        };
        $curl = curl_init($url);

        curl_setopt_array($curl, [
            CURLOPT_HEADER         => 0,
            CURLOPT_POST           => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POSTFIELDS     => http_build_query($data),
            CURLOPT_HEADERFUNCTION => $handleHeaderLine
        ]);
        $re = curl_exec($curl);
        \Log::info('NeoResponse:' . json_encode(array_merge($headers, [$re]), JSON_UNESCAPED_UNICODE));
        return [$headers, $re];

    }

}
