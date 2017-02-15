<?php

namespace App\Utils;


/*
 * 计算器
 */

use App\Models\InvitationCodes;
use App\Models\Project;
use Carbon\Carbon;

class CalculatorUtil
{

    /**
     * 获取总的待收利息
     * @param      $amount 投资本金
     * @param      $rate 年化利率
     * @param      $duration 标的周期
     * @param  int $repay_way 还款方式
     * @param      $repayWay 还款方式
     * @return int
     */
    public static function interest($amount, $rate, $duration, $repay_way = -1)
    {
        switch ($repay_way) {
            case Project::REPAY_DEBX:
                $month_rate = $rate / 12;
                $pow = pow((1 + $month_rate), $duration);
                $monthRepay = $amount * $month_rate * $pow / ($pow - 1);
                return round(bcsub($monthRepay * $duration, $amount), 2);
            case Project::REPAY_DBDX:
                return round($duration * $amount * $rate / 12, 2);
            case Project::REPAY_ALL_ZD://额外多算一天
                return round(($duration+1) * $amount * $rate / 360, 2);
            default:
                return round($duration * $amount * $rate / 360, 2);
        }
    }

    /**
     * 每月还款
     * @param     $amount
     * @param     $year_rate
     * @param     $months
     * @param int $way 还款方式
     * @param int $returned 已还本金
     * @param int $i 第几个还款月，当需要计算等额本息的某个月的利息时候
     * @return float
     */
    public static function repayMonthly($amount, $year_rate, $months, $way, $returned = 0, $i = 1)
    {
        switch ($way) {
            case Project::REPAY_DEBX:
                /*
                每月月供额=〔贷款本金×月利率×(1＋月利率)＾还款月数〕÷〔(1＋月利率)＾还款月数-1〕
                每月应还利息=贷款本金×月利率×〔(1+月利率)^还款月数-(1+月利率)^(还款月序号-1)〕÷〔(1+月利率)^还款月数-1〕
                每月应还本金=贷款本金×月利率×(1+月利率)^(还款月序号-1)÷〔(1+月利率)^还款月数-1〕
                总利息=还款月数×每月月供额-贷款本金
                */
                $month_rate = $year_rate / 12;
                $pow = pow((1 + $month_rate), $months);
                $monthRepay = $amount * $month_rate * $pow / ($pow - 1);
                // 计算该月利息
                $monthInterest = round($amount * $month_rate * ($pow - pow(1 + $month_rate, $i - 1)) / ($pow - 1), 2);
                $monthPrincipal = round(bcsub($monthRepay, $monthInterest), 2);

                return [$monthInterest, $monthPrincipal];
            case Project::REPAY_DBDX:
                $interest = round($amount * $year_rate / 12, 2);
                $principal = round($amount / $months, 2);

                return [$interest, $principal];
            case Project::REPAY_DEBJ:
                $interest = round(($amount - $returned) * ($year_rate / $months), 2);
                $principal = round($amount / $months, 2);

                return [$interest, $principal];
        }
    }

    public static function getDuration($interest, $amount, $rate)
    {
        return round(($interest * 360) / ($amount * $rate));
    }

    /**
     * 好友投资返现奖励
     * @param $interest 获得利息
     * @return mixed
     */
    public static function reward($interest, $ratio = 0.1)
    {
        return round($interest * $ratio, 2);
    }

    /**
     * 逾期利息
     * @param $amount 本金
     * @param $duration 逾期天数
     * @param $type 类型 [0=>项目,1=>投资人,2=>平台]
     * @return mixed
     */
    public static function overdue($amount, $duration, $type = 0)
    {
        return round($amount * $duration * [0.0005, 0.0001, 0.0004][$type], 2);
    }

    /**
     * 逾期总金额
     * @param $project
     * @param $overdueDays
     */
    public static function overdueMoney($amount, $rate, $overdueDays, $type = 0, $isMonthly = false)
    {
        return self::interest($amount, $rate, $overdueDays, $isMonthly) + self::overdue($amount, $overdueDays, $type);
    }

    /**
     * @param $amount
     * @param $rate
     * @param Carbon $nextPayDate
     * @param Carbon $endDate
     * @param Carbon $nowDate
     * @param int $type
     * @param int $repay_way
     * @return int|mixed
     */
    public static function overdueFine($amount, $rate, Carbon $nextPayDate, Carbon $endDate, Carbon $nowDate, $type=0, $repay_way=1) {
        if($nowDate->gt($endDate)){
            return self::interest($amount, $rate, $nowDate->diffInDays($endDate), $repay_way) + self::overdue($amount, $nowDate->diffInDays($nextPayDate), $type);
        }else{
            return self::overdue($amount, $nowDate->gt($nextPayDate)?$nowDate->diffInDays($nextPayDate):0, $type);
        }
    }

    public static function withdrawServiceFee($amount)
    {
        if (config('app.debug')) {
            return 0.01;
        }
        $fee = bcmul($amount, 0.0005);

        return $fee < 2 ? 2 : $fee;
    }


    /**
     * 逾期利息
     * @param $amount 本金
     * @param $duration 逾期天数
     * @return mixed
     */
    public static function overDayFromDue($amount, $overdue)
    {
        return (int)($overdue / $amount / 0.0005);
    }

    /**
     * 债权变现服务费
     * @param $amount
     * @return mixed
     */
    public static function creditServiceFee($amount)
    {
        return 0;
//        return round($amount * 0.005, 2);
    }


    /**
     * 还款计划
     * @param $amount
     * @param Carbon $beginCarbon
     * @param $duration
     * @param $rate
     * @param $repay_way
     * @return array
     */
    public static function repayPlan($amount, Carbon $beginCarbon, $duration, $rate, $repay_way){

        $repayStart = $beginCarbon;//new Carbon($time);
        $repayEnd = $beginCarbon->copy();//new Carbon($time);

        $allInterest = CalculatorUtil::interest($amount, $rate, $duration, $repay_way);
        switch ($repay_way) {
            case Project::REPAY_ALL:
            case Project::REPAY_ALL_ZD:
                $repayEnd->addDays($duration);
                return [
                    [
                        $repayEnd->toDateString(),
                        '利息',
                        $allInterest,
                        "interest"=>   $allInterest
                    ],
                    [$repayEnd->toDateString(), '本金', $amount,"interest"=> 0]
                ];
            case Project::REPAY_BY_MONTH:
                $repayEnd->addDays($duration);
                $plans = [];
                while ($repayStart->lt($repayEnd)) {
                    $next = $repayStart->copy()->addMonth(1);
                    $duration = $next->diffInDays($repayStart);
                    $at_end = false;
                    if ($next->gte($repayEnd)) {
                        $at_end = true;
                        $next = $repayEnd;
                    }
                    if ($at_end) {
                        array_push($plans, [$next->toDateString(), '利息', $allInterest,"interest"=>   $allInterest]);
                        array_push($plans, [$repayEnd->toDateString(), '本金', $amount,"interest"=>0]);
                    } else {
                        $value = CalculatorUtil::interest($amount, $rate, $duration);
                        $allInterest = round($allInterest - $value, 2);
                        array_push($plans, [$next->toDateString(), '利息', $value,"interest"=>   $value]);
                    }
                    $repayStart = $next;
                }

                return $plans;
            case Project::REPAY_DBDX:
            case Project::REPAY_DEBX:
            case Project::REPAY_DEBJ:
                $repayEnd->addMonth($duration);
                $plans = [];
                $allPrincipal = $amount;
                $i = 1;
                while ($repayStart->lt($repayEnd)) {
                    $next = $repayStart->copy()->addMonth(1);
                    $monthlyValue = CalculatorUtil::repayMonthly($amount, $rate, $duration, $repay_way, 0, $i);
                    $i++;
                    if ($next->gte($repayEnd)) {
                        $next = $repayEnd;
                        $arr = [
                            $next->toDateString(),
                            '本息',
                            $allInterest + $allPrincipal,
                            "interest"=>   $allInterest,
                        ];
                        array_push($plans, $arr);
                    } else {
                        $allInterest -= $monthlyValue[0];
                        $allPrincipal -= $monthlyValue[1];
                        $arr = [
                            $next->toDateString(),
                            '本息',
                            $monthlyValue[0] + $monthlyValue[1],
                             "interest"=>   $monthlyValue[0],
                        ];
                        array_push($plans, $arr);
                    }
                    $repayStart = $next;
                }

                return $plans;
        }

    }
}