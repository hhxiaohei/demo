<?php
namespace App\Utils;

use App\Models\Account;
use App\Models\UserDetail;
use App\Models\YeepayLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\Config;
use Log;

class Yeepay
{

    //用户类型
    /**
     * 个人会员
     */
    const MEMBER = 'MEMBER';
    /**
     * 商户
     */
    const MERCHANT = 'MERCHANT';

    //会员类型
    const PERSONAL = 'PERSONAL'; //个人会员
    const ENTERPRISE = 'ENTERPRISE'; //企业会员
    const GUARANTEE_CORP = 'GUARANTEE_CORP'; //担保公司

   //TENDER 投标
   //REPAYMENT 还款
   //CREDIT_ASSIGNMENT 债权变现
   //TRANSFER 转账
   //COMMISSION 分润,仅在资金转账明细中使用
    const TENDER = 'TENDER';
    const REPAYMENT = 'REPAYMENT';
    const CREDIT_ASSIGNMENT = 'CREDIT_ASSIGNMENT';
    const AUTO_TRANSACTION = 'AUTO_TRANSACTION';
    const COMMISSION = 'COMMISSION';

    //1 成功
    //0 失败
    //2 xml 参数格式错误
    //3 签名验证失败
    //101 引用了不存在的对象(例如错误的订单号)
    //102 业务状态不正确
    //103 由于业务限制导致业务不能执行
    //￼104 实名认证失败
    const SUCCESS = '1';
    const FAILED = '0';
    const XML_ERROR = '2';
    const SIGN_ERROR = '3';
    const NOT_FOUND = '101';
    const STATUS_ERROR = '102';
    const BUSINESS_LIMITED = '103';
    const TRUENAME_VERIFY_FAILED = '104';

    const TO_AUTHORIZE_AUTO_TRANSFER = 'toAuthorizeAutoTransfer';
    const FREEZE = 'FREEZE';
    const UNFREEZE = 'UNFREEZE';
    const URGENT = 'URGENT';
    const DIRECT_TRANSACTION = 'DIRECT_TRANSACTION';
    const FEE_MODE_USER = 'USER';
    const FEE_MODE_PLATFORM = 'PLATFORM';

    const TO_CP_TRANSACTION = 'toCpTransaction';
    const TO_REGISTER = 'toRegister';
    const TO_RECHARGE = 'toRecharge';
    const TO_WITHDRAW = 'toWithdraw';
    const TO_ENTERPRISE_REGISTER = 'toEnterpriseRegister';
    const TO_BIND_BANK_CARD = 'toBindBankCard';
    const TO_UNBIND_BANK_CARD = 'toUnbindBankCard';
    const TO_AUTHORIZE_AUTO_REPAYMENT = 'toAuthorizeAutoRepayment';

    /**
     * sign 签名
     * @param $req 请求信息
     * @return mixed|string
     */
    public static function getSign($req)
    {
        if (config('yeepay.debug')) {
            return 'debug';
        }

        return Yeepay::sendPost(Config::get('yeepay.sign'), ['req' => $req]);
    }

    /**
     * 验证签名
     * @param $data
     * @return bool
     */
    public static function verifySign($data)
    {
        if (Config::get('yeepay.debug')) {
            return true;
        }

        $arr = [
            'req'  => array_key_exists('resp', $data) ? $data['resp'] : $data['notify'],
            'sign' => $data['sign']
        ];
        $content = Yeepay::sendPost(Config::get('yeepay.verify'), $arr);
        Log::info('VerifySign:' . json_encode($arr['req']));

        return $content == 'SUCCESS';
    }
    public static function redirectToYeepay($data, $source = '')
    {
        if (session('source')) {
            $source = session('source');
        }
        if ($source && $source == 'wap') {
            return wechat_view('yeepay.post', $data);
        }

        return website_view('yeepay.post', $data);
    }

    /**
     * curl
     * @param $url
     * @param $data
     * @return mixed
     */
    public static function sendPost($url, $data)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));

        return curl_exec($curl);
    }

    /**
     * 回调
     * @param $request
     * @param bool $isCallback
     * @return bool
     * @throws Exception
     */
    public static function callbackAndNotifyHandler($request, $isCallback = true)
    {
        if (!$isCallback) {
            $data = $request->all()['notify'];
        } else {
            $data = $request->all()['resp'];
        }
        $data = XML2Array::createArray($data);
        $service = null;
        if (!$isCallback) {
            $res = $data['notify'];
        } else {
            $res = $data['response'];
            $service = $res['service'];
        }
        $description = array_key_exists('description', $res) ? $res['description'] : '';
        $message = array_key_exists('message', $res) ? $res['message'] : '';
        $platformUserNo = array_key_exists('platformUserNo', $res) ? $res['platformUserNo'] : '';
        $requestNo = $res['requestNo'];
        $code = $res['code'];
        Log::info('YeepayCallbackResponse:' . $isCallback . '|' . json_encode($res));
        return \DB::transaction(function () use($isCallback,$requestNo,$res,$code,$description,$message,$platformUserNo){
            $log = YeepayLog::find(Utils::hashidDecode($requestNo));
            if (array_key_exists('payProduct', $res) && !$log->description) {
                $log->description = $res['payProduct'];
            }
            if (array_key_exists('bizType', $res) && !$log->biz_type) {
                $log->biz_type = $res['bizType'];
            }
            if ($isCallback) {
                $log->callback_at = Carbon::now()->toDateTimeString();
            } else {
                $log->notify_at = Carbon::now()->toDateTimeString();
            }
            $log->code = $code;
            if ($code == Yeepay::SUCCESS) {
                $log->save();
//                $log = YeepayLog::find($log->id);
                Log::info('YeepayCallbackOrNotifyOK:' . ($isCallback ? 'callback' : 'notify') . '|' . $log);
                return $log;
            }
            if ($description) {
                $log->description = $description;
            }
            if ($message) {
                $log->message = $message;
            }
            if (isset($service) && $service) {
                $log->service = $service;
            }
            if (!Config::get('yeepay.debug') && $platformUserNo) {
                $log->user_id = hashid_decode($platformUserNo);
            }
            $log->save();
            Log::info('YeepayCallbackOrNotifySuccess:' . ($isCallback ? 'callback' : 'notify') . '|' . $log);
            return $log;

        });

    }

//*****************************
// 直连接口
//*****************************

    /**
     * 同步易宝账户于平台账户数据
     * @param $platformUserNo 平台用户
     * @return mixed
     */
    public static function syncAccount($platformUserNo)
    {
        $uri = 'ACCOUNT_INFO';
        $result = Yeepay::directRequest($uri, ['platformUserNo' => $platformUserNo]);
        $response = $result['response'];
        $status = $response['code'] == Yeepay::SUCCESS;
        if ($status) {
            $user_id = Utils::hashidDecode($platformUserNo);
            $userDetail = UserDetail::find($user_id);
            $userDetailSaved = false;
            if ($userDetail) {
                $userDetailSaved = Utils::fillModelWithArray($userDetail, $response,['bank_card_number', 'bank_card_status','bank']);
            }
            $userAccountSaved = false;
            $account = Account::find($user_id);
            if ($account) {
                if ($response['freezeAmount']) {
                    $response['balance'] = bcsub($response['balance'], $response['freezeAmount']);
                }
                $userAccountSaved = Utils::fillModelWithArray($account, $response);
            }
            Log::info('SyncedAccount:' . $platformUserNo . '|' . $userDetailSaved . '|' . $userAccountSaved);
        }

        return $result;
    }

    /**
     * 冻结资金
     * @param $data
     * @return bool
     * @throws \Exception
     */
    public static function freeze($data)
    {
        $uri = 'FREEZE';
        $log = YeepayLog::generate($uri, $data,true);
        $data['requestNo'] = $log->eid;
        $result = Yeepay::directRequest($uri, $data);
        $response = $result['response'];
        $status = $response['code'] == Yeepay::SUCCESS;
        if (!$status) {
            Log::error('FreezeFailed:' . json_encode($data));
        }else{
            self::updateLog($log, $response);
        }

        return $status;
    }

    private static function updateLog($log, $response)
    {
        $log->code = (int)$response['code'];
        $log->description = $response['description'];
        $log->handled = true;
        try {
            $log->save();
        } catch (\Exception $e) {
        }
    }


    /**
     * 解冻资金
     * @param $data
     * @return bool
     */
    public static function unfreeze($data)
    {
        $uri = 'UNFREEZE';
        if (array_key_exists('request_no', $data)) {
            $data['freezeRequestNo'] = $data['request_no'];
        }
        $result = Yeepay::directRequest($uri, $data);
        $response = $result['response'];
        $status = $response['code'] == Yeepay::SUCCESS;
        if (!$status) {
            Log::error('UnfreezeFailed:' . json_encode($data));
        }else{
            YeepayLog::find(Utils::hashidDecode($data['freezeRequestNo']))->update(['status' => 1]);
        }

        return $status;
    }

    /**
     * 从商户转账到个人账户，直接转账
     * 当log 存在 不生成新log
     * @param $data
     */
    public static function directTransaction($data,$log=null)
    {
        $uri = 'DIRECT_TRANSACTION';
        $data['userType'] = Yeepay::MERCHANT;
        $data['bizType'] = 'TRANSFER';
        $data['targetUserType'] = Yeepay::MEMBER;
        $log = $log?:YeepayLog::generate($uri, $data, true);
        $data['requestNo'] = Utils::hashidEncode($log->id);
        $data['platformUserNo'] = Config::get('yeepay.platformNo');
        $result = Yeepay::directRequest($uri, $data);
        $response = $result['response'];
        $status = $response['code'] == Yeepay::SUCCESS;
        $userDetailSaved = Utils::fillModelWithArray($log, $response);
        Log::info('LogUpdatedAfterDirectTransaction:' . $userDetailSaved);
        if ($status) {
            Log::info('DIRECT_TRANSACTION_SUCCESS:' . json_encode($response));
        } else {
            Log::warning('DIRECT_TRANSACTION_FAILED' . json_encode($response));
        }

        $data = [
            'status'    => $status,
            'requestNo' => $data['requestNo'],
            'log'       => $log,
            'response' => $response
        ];

        return $data;
    }

    /**
     * 自动转账授权
     * @param $data
     */
    public static function autoTransaction($data)
    {
        $uri = 'AUTO_TRANSACTION';
        $data['targetUserType'] = Yeepay::MEMBER;
        $log = YeepayLog::generate($uri, $data, true);
        $data['requestNo'] = Utils::hashidEncode($log->id);
        $result = Yeepay::directRequest($uri, $data);
        $response = $result['response'];
        $status = $response['code'] == Yeepay::SUCCESS;
        $logSaved = Utils::fillModelWithArray($log, $response);
        Log::info('AutoTransactionLogUpdated:'.$logSaved);
        if ($status) {
            Log::info('AUTO_TRANSACTION_SUCCESS:' . json_encode($response));
        }else{
            Log::warning('AUTO_TRANSACTION_FAILED' . json_encode($response));
        }

        return [
            'status'    => $status,
            'requestNo' => $data['requestNo'],
            'log'       => $log,
            'response'  => $response
        ];
    }


    /**
     * 单笔业务查询
     * mode: WITHDRAW_RECORD:提现记录 RECHARGE_RECORD:充值记录
     * CP_TRANSACTION:划款记录 FREEZERE_RECORD:冻结/解冻接口
     * @param $data
     */
    public static function directQuery($data)
    {
        $uri = 'QUERY';
        if (!array_key_exists('requestNo', $data)) {
            $data['requestNo'] = YeepayLog::generate($uri, $data);
        }
        $result = Yeepay::directRequest($uri, $data);
        $response = $result['response'];
        $status = $response['code'] == Yeepay::SUCCESS;
        if ($status) {

        }

        return $response;
    }

    /**
     * 转账确认
     * @param $data
     */
    public static function completeTransaction($data)
    {
        $uri = 'COMPLETE_TRANSACTION';
        $result = Yeepay::directRequest($uri, $data);
        $response = $result['response'];
        $status = $response['code'] == Yeepay::SUCCESS;
        if (!$status) {
            Log::error('CompleteError:'.json_encode($data).'|'. json_encode($result));
        }else{
            Log::info('CompleteSuccess:'.json_encode($data));
        }

        return $status;
    }

    /**
     * 解绑银行卡 直连接口
     * @param $data
     * @return mix
     */
    public static function unBindCard($data)
    {
        $uri = 'UNBIND_CARD';
        $log =  YeepayLog::generate($uri, $data,true);
        $data['requestNo'] =  Utils::hashidEncode($log->id);
        $result = Yeepay::directRequest($uri, $data);
        $response = $result['response'];

        if($response['code'] == Yeepay::SUCCESS && $response['description']) {
            return $response['description'] . ' 将于两个自然日后自动生效';
        }elseif($response['code'] == Yeepay::NOT_FOUND && $response['description']) {
            $log->code = $response['code'];
            $log->update();
            return $response['description'];
        }else {
            return false;
        }

    }

    /**
     * 取消自动投标授权
     * @param $data
     * @return bool
     */
    public static function cancelAutoTransaction($data)
    {
        $uri = 'CANCEL_AUTHORIZE_AUTO_TRANSFER';
        $result = Yeepay::directRequest($uri, $data);
        $response = $result['response'];
        Log::info('CANCEL_AUTHORIZE_AUTO_TRANSFER:'.json_encode($response));
        $status = $response['code'] == Yeepay::SUCCESS;
        if ($status) {

        }

        return $status;
    }

    /**
     * 取消自动还款授权
     * @param $data
     * @return bool
     */
    public static function cancelAutoRepayment($data)
    {
        $uri = 'CANCEL_AUTHORIZE_AUTO_REPAYMENT';
        $data['requestNo'] = YeepayLog::generate($uri, $data);
        $result = Yeepay::directRequest($uri, $data);
        $response = $result['response'];
        $status = $response['code'] == Yeepay::SUCCESS;
        if ($status) {

        }

        return $status;
    }

    /**
     * 组合xml数据，连接易宝接口，获得回调数据
     * @param $uri
     * @param $data
     * @return \DomDocument
     * @throws Exception
     */
    public static function directRequest($uri, $data)
    {
        $req = Yeepay::getXml($uri, $data);
        $sign = Yeepay::getSign($req);
        $result = Yeepay::sendPost(Config::get('yeepay.direct_host')
            , [
                'service' => $uri,
                'req'     => $req,
                'sign'    => $sign
            ]);

        Log::info('DirectRequestReq:' . $uri . '|' . json_encode($data) . '|' . $req);
        Log::info('DirectRequestResult:' . $uri . '|' . json_encode($data) . '|' . $result);

        return XML2Array::createArray($result);
    }


//*****************************
// 网关接口
//*****************************

    /**
     * 用户注册
     * @param $data
     * @return array
     */
    public static function regist($data)
    {
        $uri = YeepayToRegister;
//        $data['email'] = $data['platformUserNo'] . '@user.nxdai.com';

        return Yeepay::composeData($uri, $data);
    }
    /**
     * 修改手机号
     * @param $data
     * @return array
     */
    public static function modifyMobile($data)
    {
        $uri = YeepayToModifyMobile;
//        $data['email'] = $data['platformUserNo'] . '@user.nxdai.com';

        return Yeepay::composeData($uri, $data);
    }
    /**
     * 充值
     * @param $data
     * @return array
     */
    public static function recharge($data)
    {
        $uri = YeepayToRecharge;

        return Yeepay::composeData($uri, $data);
    }

    /**
     * 提现
     * @param $data
     * @return array
     */
    public static function withdraw($data)
    {
        $uri = YeepayToWithdraw;

        return Yeepay::composeData($uri, $data);
    }

    /**
     * 企业用户注册
     * @param $data
     * @return array
     */
    public static function toEnterPriseRegister($data){
        $uri = YeepayToEnterpriseRegister;

        return Yeepay::composeData($uri, $data);
    }
    /**
     * 绑卡
     * @param $data
     * @return array
     */
    public static function bindBankCard($data)
    {
        $uri = YeepayToBindBankCard;

        return Yeepay::composeData($uri, $data);
    }

    /**
     * 解绑卡
     * @param $data
     * @return array
     */
    public static function unbindBankCard($data)
    {
        $uri = YeepayToUnbindBankCard;

        return Yeepay::composeData($uri, $data);
    }

    /**
     * 自动还款授权
     * @param $data
     * @return array
     */
    public static function toAuthorizeAutoRepayment($data)
    {
        return Yeepay::composeData(YeepayToAuthorizeAutoRepayment, $data);
    }

    /**
     * 自动转账授权
     * @param $data
     * @return array
     */
    public static function toAuthorizeAutoTransfer($data)
    {
        return Yeepay::composeData(Yeepay::TO_AUTHORIZE_AUTO_TRANSFER, $data);
    }

    //转账授权
    /**
     * 转账
     * @param $data
     * @return array
     */
    public static function transform($data)
    {
        $data['bizType'] = YeepayBizTypeTransfer;

        return Yeepay::composeData('toCpTransaction', $data);
    }

    /**
     * 投标
     * @param $data
     */
    public static function tender($data)
    {
        $data['bizType'] = YeepayBizTypeTender;
        return Yeepay::composeData('toCpTransaction', $data);
    }

    /**
     * 债权变现
     * @param $data
     * @return array
     */
    public static function credit($data)
    {
        $data['bizType'] = YeepayCreditAssignment;
        $data['callbackUrl'] = route('wechat.invest_record');
        if(empty($data['expired']))
        {
            $data['expired'] = Carbon::now()->addSeconds(config("nxd.credit_invest_expire"))->toDateTimeString();
        }
        return Yeepay::composeData('toCpTransaction', $data);
    }

    /**
     * 还款
     * @param [
     *  'fee'=>'还款的时候平台收取的费用'
     * ]
     */
    public static function repay($data)
    {
        return Yeepay::composeData('toCpTransaction', $data);
    }

    public static function composeData($uri, $data)
    {
        if (!array_key_exists('source', $data)) {
            $data['source'] = session('source');
        } elseif (!$data['source']) {
            $data['source'] = session('source');
        }
        $data['requestNo'] = YeepayLog::generate($uri, $data);
        if ($data['source'] && $data['source'] != 'web') {
            $url = Config::get('yeepay.mobile_host') . $uri;
        } else {
            $url = Config::get('yeepay.host') . $uri;
        }
        $xml = Yeepay::getXml($uri, $data);
        $sign = Yeepay::getSign($xml);
        Log::info('ComposeData:' . $uri . '|' . $xml);

        return compact('url', 'xml', 'sign');
    }

    /**
     * 组合待发送XML
     * @param $uri
     * @param $data
     * @return string
     * platformNo 商户编号
     * requestNo 请求流水号
     * callbackUrl 页面回跳
     * notifyUrl 回调通知
     * platformUserNo 用户编号
     * feeMode 见费率模式
     * expired 时间限制
     * amount 金额
     * bizType 交易类型
     * tenderOrderNo 项目编号
     * tenderName 项目名称
     * tenderAmount 项目金额
     * tenderDescription 项目描述信息
     * borrowerPlatformUserNo 项目的借款人平台用户编号
     * tenderSumLimit 累计投标金额限制
     */
    public static function getXml($uri, $data)
    {
        $start = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>';
        $platformNo = '<request platformNo="' . Config::get("yeepay.platformNo") . '">';
        $requestNo = Yeepay::getDataNode('requestNo', $data);
        $callback = Yeepay::getConfigNode('callbackUrl', $uri);
        $notify = Yeepay::getConfigNode('notifyUrl', $uri);
        $userNo = Yeepay::getDataNode('platformUserNo', $data);
        $feeMode = isset($data["feeMode"])?Yeepay::getDataNode('feeMode', $data):Yeepay::getConfigNode('feeMode');
        $expired = Yeepay::getDataNode('expired', $data);
        $amount = Yeepay::getDataNode('amount', $data);
        $bizType = Yeepay::getDataNode('bizType', $data);
        $end = '</request>';
        $common = $start . $platformNo . $requestNo . $callback . $notify . $userNo . $feeMode;
        switch ($uri) {
            //浏览器网关接口
            //注册
            case YeepayToRegister:
                $nickName = Yeepay::getDataNode('realName', $data);
                $realName = Yeepay::getDataNode('realName', $data);
                $idCardType = Yeepay::getConfigNode('idCardType');
                $idCardNo = Yeepay::getDataNode('idCardNo', $data);
                $mobile = Yeepay::getDataNode('mobile', $data);
//                $email = Yeepay::getDataNode('email', $data);

                return $common . $nickName . $realName
                . $idCardType . $idCardNo . $mobile . $end;
            //注册
            case YeepayToModifyMobile:
                $mobile = Yeepay::getDataNode('mobile', $data);
//                $email = Yeepay::getDataNode('email', $data);

                return $common .$mobile. $end;
            //充值
            case YeepayToRecharge:
                $amount = Yeepay::getDataNode('amount', $data);

                return $common . $amount . $end;
            case YeepayToEnterpriseRegister:

                $enterpriseName = Yeepay::getDataNode('enterpriseName', $data);
                $bankLicense = Yeepay::getDataNode('bankLicense', $data);
                $orgNo = Yeepay::getDataNode('orgNo', $data);
                $businessLicense = Yeepay::getDataNode('businessLicense', $data);
                $taxNo = Yeepay::getDataNode('taxNo', $data);
                $legal = Yeepay::getDataNode('legal', $data);
                $legalIdNo = Yeepay::getDataNode('legalIdNo', $data);
                $contact = Yeepay::getDataNode('contact', $data);
                $contactPhone = Yeepay::getDataNode('contactPhone', $data);
                $email = Yeepay::getDataNode('email', $data);
                $memberClassType = Yeepay::getDataNode('memberClassType', $data);
                $companyRegister = $enterpriseName . $bankLicense . $orgNo . $businessLicense . $taxNo . $legal . $legalIdNo . $contact . $contactPhone . $email . $memberClassType;

                return $start . $platformNo . $requestNo . $callback . $notify . $userNo . $companyRegister . $end;
            //提现
            case YeepayToWithdraw:
                $withdrawType = Yeepay::getDataNode('withdrawType', $data);
                return $common . $expired . $amount . $withdrawType . $end;
            //绑定银行卡
            case YeepayToBindBankCard:
                return $start . $platformNo . $requestNo . $notify . $callback . $userNo . $end;
            //解绑银行卡
            case YeepayToUnbindBankCard:
                return $start . $platformNo . $requestNo . $callback . $userNo . $end;
            //自动还款授权
            case YeepayToAuthorizeAutoRepayment:
                $orderNo = Yeepay::getDataNode('orderNo', $data);

                return $start . $platformNo . $requestNo . $orderNo . $notify . $callback . $userNo . $end;
            //直连接口
            //账户查询
            case 'ACCOUNT_INFO':
                return $start . $platformNo . $userNo . $end;
            //资金冻结
            case 'FREEZE':
                return $start . $platformNo . $requestNo . $userNo . $amount . $expired . $end;
            //资金解冻
            case 'UNFREEZE':
                $freezeRequestNo = Yeepay::getDataNode('freezeRequestNo', $data);

                return $start . $platformNo . $freezeRequestNo . $end;
            //单笔业务查询
            case 'QUERY':
                $mode = Yeepay::getDataNode('mode', $data);

                return $start . $platformNo . $requestNo . $mode . $end;
            //转账确认
            case 'COMPLETE_TRANSACTION':
                $mode = Yeepay::getDataNode('mode', $data);
                return $start . $platformNo . $requestNo . $mode . $notify . $end;
            case Yeepay::TO_AUTHORIZE_AUTO_TRANSFER:
                return $start . $platformNo . $userNo . $requestNo . $callback .$notify . $end;
            //取消自动投标授权
            case 'CANCEL_AUTHORIZE_AUTO_TRANSFER':
                return $start . $platformNo . $userNo . $requestNo . $end;
            //取消自动还款授权
            case 'CANCEL_AUTHORIZE_AUTO_REPAYMENT':
                $orderNo = Yeepay::getDataNode('orderNo', $data);

                return $start . $platformNo . $userNo . $requestNo . $orderNo . $end;
            case 'UNBIND_CARD':
                return $start . $platformNo . $userNo . $requestNo . $end;
            //直接转账
            case 'DIRECT_TRANSACTION':
                $targetUserType = Yeepay::getDataNode('targetUserType', Yeepay::MEMBER);
                $details='<details>';
                if(isset($data["details"])){
                    foreach ($data['details'] as $detail) {
                        $details .= '<detail>'.
                            Yeepay::getDataNode('targetUserType', $detail) .
                            Yeepay::getDataNode('targetPlatformUserNo', $detail) .
                            Yeepay::getDataNode('amount', $detail) .
//                            Yeepay::getDataNode('bizType', $detail) .
                            $bizType.
                            '</detail>';
                    }
                    $details .= '</details>';
                }else{
                    $targetPlatformUserNo = Yeepay::getDataNode('targetPlatformUserNo', $data);
                    $details = '<details><detail>' . $amount . $targetUserType . $targetPlatformUserNo . $bizType . '</detail></details>';
                }
                $userType = Yeepay::getDataNode('userType', $data);
                $platformUserNo = Yeepay::getDataNode('platformUserNo', $data);
                return $start . $platformNo . $requestNo . $platformUserNo . $userType . $bizType . $details . $notify . $end;
            //自动转账授权
            case 'AUTO_TRANSACTION':
                $userType = Yeepay::getDataNode('userType', $data);
                $details = '<details>';

                foreach ($data['details'] as $detail) {
                    $details .= '<detail>'.
                        Yeepay::getDataNode('targetUserType', $detail) .
                        Yeepay::getDataNode('targetPlatformUserNo', $detail) .
                        Yeepay::getDataNode('amount', $detail) .
                        Yeepay::getDataNode('bizType', $detail) .
                        '</detail>';
                }

                $details .= '</details>';

                if ($data['bizType'] == self::REPAYMENT) {
                    $extend = '<extend>' .
                        Yeepay::getPropertyNode('tenderOrderNo', $data) .
                        '</extend>';
                }else{
                    $extend = '<extend>' .
                        Yeepay::getPropertyNode('tenderOrderNo', $data) .
                        Yeepay::getPropertyNode('tenderName', $data) .
                        Yeepay::getPropertyNode('tenderAmount', $data) .
                        Yeepay::getPropertyNode('tenderDescription', $data) .
                        Yeepay::getPropertyNode('borrowerPlatformUserNo', $data) .
                        Yeepay::getPropertyNode('tenderSumLimit', $data) .
                        '</extend>';
                }

                $common = $start . $platformNo . $requestNo . $userNo
                    . $userType . $bizType . $notify . $callback;
                return $common . $details . $extend . $end;
            //转账授权（浏览器网关接口）
            case 'toCpTransaction':
                $ut = array_key_exists('userType', $data) ? $data['userType'] : Yeepay::MEMBER;
                $userType = Yeepay::getDataNode('userType', $ut);
                $tut = array_key_exists('targetUserType', $data) ? $data['targetUserType'] : Yeepay::MEMBER;
                $targetUserType = Yeepay::getDataNode('targetUserType', $tut);
                $targetPlatformUserNo = Yeepay::getDataNode('targetPlatformUserNo', $data);
                $expired = Yeepay::getDataNode('expired', $data);
                $amount = Yeepay::getDataNode('amount', $data);
                //资金明细记录
                $detail = '<details><detail>' . $amount . $targetUserType . $targetPlatformUserNo . $bizType . '</detail></details>';
                $common = $start . $platformNo . $requestNo . $userNo
                    . $userType . $bizType . $expired . $notify . $callback;
                switch ($data['bizType']) {
                    //转账
                    case YeepayBizTypeTransfer:
                        return $common . $detail . $end;
                    //投标
                    case YeepayBizTypeTender:
                        $extend = '<extend>' .
                            Yeepay::getPropertyNode('tenderOrderNo', $data) .
                            Yeepay::getPropertyNode('tenderName', $data) .
                            Yeepay::getPropertyNode('tenderAmount', $data) .
                            Yeepay::getPropertyNode('tenderDescription', $data) .
                            Yeepay::getPropertyNode('borrowerPlatformUserNo', $data) .
                            Yeepay::getPropertyNode('tenderSumLimit', $data) .
                            '</extend>';
                        if (array_key_exists('details', $data)) {
                            $details = '<details>';
                            foreach ($data['details'] as $item) {
                                $details .= '<detail>'.
                                    Yeepay::getDataNode('targetUserType', $item) .
                                    Yeepay::getDataNode('targetPlatformUserNo', $item) .
                                    Yeepay::getDataNode('amount', $item) .
                                    Yeepay::getDataNode('bizType', $item) .
                                    '</detail>';
                            }

                            $details .= '</details>';
                            return $common  . $details . $extend . $end;
                        }
                        return $common . $detail  . $extend . $end;
                    //还款
                    case YeepayBizTypeRepayment:
                        $details = '<details>';

                        foreach ($data['details'] as $detail) {
                            $details .= '<detail>'.
                                Yeepay::getDataNode('targetUserType', $detail) .
                                Yeepay::getDataNode('targetPlatformUserNo', $detail) .
                                Yeepay::getDataNode('amount', $detail) .
                                Yeepay::getDataNode('bizType', $detail) .
                            '</detail>';
                        }

                        $details .= '</details>';

                        $extend = '<extend>' .
                            Yeepay::getPropertyNode('tenderOrderNo', $data) .
                            '</extend>';

                        $common = $start . $platformNo . $requestNo . $userNo
                            . $userType . $bizType . $notify . $callback;
                        return $common . $details . $extend . $end;
                    //债权变现
                    case YeepayCreditAssignment:
                        $amount = Yeepay::getDataNode('amount', $data['amountAfter']);
                        $details = '<details><detail>' . $amount . $targetUserType . $targetPlatformUserNo . $bizType . '</detail>';
                        if($data['fee']) {
                            $details .= '<detail>' .
                                Yeepay::getDataNode('targetUserType', Yeepay::MERCHANT) .
                                Yeepay::getDataNode('targetPlatformUserNo', Config::get("yeepay.platformNo")) .
                                Yeepay::getDataNode('amount', $data['fee']) .
                                Yeepay::getDataNode('bizType', Yeepay::COMMISSION) .
                                '</detail>';
                        }
                        $details .= '</details>';
                        $extend = '<extend>' .
                            Yeepay::getPropertyNode('tenderOrderNo', $data) .
                            Yeepay::getPropertyNode('creditorPlatformUserNo', $data) .
                            Yeepay::getPropertyNode('originalRequestNo', $data) .
                            '</extend>';

                        return $common . $details . $extend . $end;
                }
        }
    }

    public static function getPropertyNode($key, $value)
    {
        return '<property name="' . $key . '" value="' . $value[$key] . '" />';
    }

    public static function getConfigNode($key, $prefix = '')
    {

        $name = $prefix ? $prefix . '_' . $key : $key;

        return '<' . $key . '>' . Config::get('yeepay.' . $name) . '</' . $key . '>';
    }

    public static function getDataNode($key, $data)
    {
        if ($key == 'amount' && is_array($data) && array_key_exists($key, $data)) {
            $data['amount'] = round($data['amount'], 2);
        }
        if (is_array($data)) {
            return array_key_exists($key, $data) ? '<' . $key . '>' . $data[$key] . '</' . $key . '>' : '';
        } else {
            return $data ? '<' . $key . '>' . $data . '</' . $key . '>' : '';
        }
    }
}