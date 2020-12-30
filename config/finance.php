<?php
/**
 * 财务模块公共配置
 *
 * Created by PhpStorm.
 * Name: cjx
 * Date: 2020/05/02
 */

return [

    //资金明细类型
   'finance_fund_type' =>  [
       FINANCE_CHANGE_TYPE_TRADE        =>      '交易',
       FINANCE_CHANGE_TYPE_RECHARGE     =>      '充值',
       FINANCE_CHANGE_TYPE_REFUND       =>      '退款',
       FINANCE_CHANGE_TYPE_SERVICES     =>      '手续费',
       FINANCE_CHANGE_TYPE_GIVE         =>      '赠送',
       FINANCE_CHANGE_TYPE_CASH         =>      '提现',
       FINANCE_CHANGE_TYPE_CHECK        =>      '冲正',
       FINANCE_CHANGE_TYPE_FROZEN       =>      '冻结',
   ],

    //资金类型
    'finance_fund_change_type'  =>  [
        FINANCE_INCOME  =>  '收入',
        FINANCE_EXPEND  =>  '支出',
    ],

];