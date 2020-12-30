<?php
namespace App\Services\Orders;

use App\Repositories\SaasCompoundQueueRepository;
use App\Repositories\SaasCustomerBalanceLogRepository;
use App\Repositories\SaasDownloadQueueRepository;
use App\Repositories\SaasOrderProduceQueueRepository;
use App\Repositories\SaasOrdersRepository;
use App\Repositories\SaasPaymentRepository;
use App\Services\ChanelUser;
use App\Services\Helper;

/**
 * 订单创建后流程
 *
 * 订单创建后的处理
 * @author: yanxs <541139655@qq.com>
 * @version: 1.0
 * @date: 2020/4/22
 */

class AfterCreate
{
    protected $repoOrder;
    protected $repoPayment;
    protected $repoBalance;
    protected $repoCompQueue;
    protected $repoDownQueue;
    protected $repoProduceQueue;
    /**
     * AfterCreate constructor.
     * @param SaasOrdersRepository $order
     * @param SaasPaymentRepository $payment
     * @param SaasCustomerBalanceLogRepository $balance
     * @param SaasCompoundQueueRepository $compQueue
     * @param SaasDownloadQueueRepository $downQueue
     * @param SaasOrderProduceQueueRepository $produceQueue
     */
    public function __construct(SaasOrdersRepository $order, SaasPaymentRepository $payment,
        SaasCustomerBalanceLogRepository $balance,SaasCompoundQueueRepository $compQueue,
        SaasDownloadQueueRepository $downQueue, SaasOrderProduceQueueRepository $produceQueue)
    {
        $this->repoOrder = $order;
        $this->repoPayment = $payment;
        $this->repoBalance = $balance;
        $this->repoCompQueue = $compQueue;
        $this->repoDownQueue = $downQueue;
        $this->repoProduceQueue = $produceQueue;
    }

    /**
     * 余额支付逻辑,其他支付独立逻辑
     * @param $orderId 订单号
     * @param $userId 会员名称
     * @param $userType 会员类型
     * @return mixed
     */
    public function  balancePay($orderId, $userId, $userType)
    {
        //余额写法
        $userModel = app(ChanelUser::class)->getUserInfo($userId, $userType);
        if ($userType == CHANEL_TERMINAL_AGENT) {
            $balance = $userModel['agent_balance'];
        } else {
            $balance = $userModel['balance'];
        }
        $orderModel = $this->repoOrder->getById($orderId);
        if (empty($orderModel)) {
            Helper::EasyThrowException('70050',__FILE__.__LINE__);
        }
        $needPay = $orderModel->order_real_total;
        //余额是否够支付
        if ($balance < $needPay) {
            Helper::EasyThrowException('70051',__FILE__.__LINE__);
        }
        //扣除余额
        $return = app(ChanelUser::class)->updateBalance($userId, $userType, (0-$needPay));

        if(empty($return)) { //可能出现并发情况下的余额不足判断
            Helper::EasyThrowException('70051',__FILE__.__LINE__);
        }
        //订单支付日志
        $logData = [
            'order_no'   => $orderModel->order_no,
            'user_id'    => $userId,
            'user_type'  => $userType,
            'amount'     => $needPay,
            'pay_type'   => $orderModel->order_pay_id,
            'pay_status' => PUBLIC_YES,
            'created_at' => time()
        ];
        $payLogModel = $this->repoPayment->recordPayLog($logData);
        //资金变动日志
        $balanceLogData = [
            'mch_id'                     => $orderModel->mch_id,
            'user_id'                    => $userId,
            'user_type'                  => $userType,
            'operate_type'               => OPERATE_TYPE_USER,
            'operate_id'                 => $userId,
            'cus_balance_type'           => FINANCE_EXPEND,
            'cus_balance_type_detail'    => FINANCE_CHANGE_TYPE_TRADE,
            'cus_balance_change'         => $needPay,
            'cus_balance'                => $balance-$needPay,
            'cus_balance_frozen_change'  => 0,
            'cus_balance_frozen'         => 0,
            'cus_balance_business_no'    => $orderModel->order_no,
            'pay_id'                     => $orderModel->order_pay_id,
            'remark'                     => '',
            'created_at'                 => time()

        ];
        $balanceModel = $this->repoBalance->insert($balanceLogData);

        return true;
    }

    /**
     * 创建订单日志
     * @param $data 日志数据
     */
    public function recordOrderLog($data)
    {
        RETURN $this->repoOrder->recordOrderLog($data);
    }

    /**
     * 创建合成队列
     * @param $data
     * @return mixed
     */
    public function createCompoundQueue($data)
    {
        return $this->repoCompQueue->insert($data);
    }

    /**
     * 创建下载队列
     * @param $data
     * @return mixed
     */
    public function createDownloadQueue($data)
    {
        return $this->repoDownQueue->insert($data);
    }

    /**
     * 创建生产队列
     * @param $data
     */
    public function createProduceQueue($data)
    {
        $this->repoProduceQueue->insert($data);
    }
}