<?php
namespace App\Presenters;
/**
 * 公共模板状态处理类
 *
 * 处理view层相应转换逻辑
 * @author: yanxs <541139655@qq.com>
 * @version: 1.0
 * @date: 2019/8/14
 * @revision: m1 cjx 2020/01/02 1与0转换为成功与失败
 *            m2 cjx 2020/01/02 1与2转换为支付宝与微信
 *            m3 cjx 2020/03/18 快递名称转换
 *            m4 ljh 2020/04/07 获取快递类型
 *            m5 ljh 2020/04/07 获取邮件连接类型
 *            m6 ljh 2020/04/07 获取邮件使用场景
 *            m7 cjx 2020/04/14 订单生产状态装换
 *            m8 cjx 2020/04/14 订单总状态装换
 *            m9 cjx 2020/04/20 订单支付状态转换
 *            m10 cjx 2020/04/20 订单确认状态转换
 *            m11 cjx 2020/04/20 订单发货状态转换
 *            m12 cjx 2020/04/21 发票信息转换
 *            m13 cjx 2020/04/23 售后类型转换
 *            m14 cjx 2020/04/23 处理、未处理状态转换
 *            m15 cjx 2020/04/24 售后处理方式转换
 *            m16 cjx 2020/05/02 资金明细类型转换
 *            m17 cjx 2020/05/02 资金类型转换
 *            m18 cjx 2020/07/03 供货商订单状态转换
 */
class CommonPresenter
{
    /**
     * 获取是与否的值
     * @param int $status 状态值 0否 1是
     * @return string
     */
    public function getYesOrNo($status)
    {
        $text = !empty($status) ? __("common.yes") : __("common.no");
        return $text;
    }

    /**
     * 获取启用、禁用、锁定的值
     * @param int $status 状态值 0禁用 1启用 3锁定
     * @return string
     */
    public function getEnabledOrDisabled($status)
    {
        if ($status == PUBLIC_LOCK){
            return __("common.lock");
        }
        $text = $status == PUBLIC_ENABLE ? __("common.enabled") : __("common.disabled");
        return $text;
    }
    /**
     * 时间戳转换
     * @param int $time 时间戳
     * @return string
     */
    public function exchangeTime($time)
    {
        $text = '';
        if(isset($time)){
            $text = date("Y-m-d H:i:s",$time);
        }
        return $text;

    }


    /**
     * 获取 正常 或 隐藏状态
     * @param string $string 状态值 hidden隐藏 normal正常
     * @return string
     */
    public function getNormalOrHidden($string)
    {
        if ($string == "normal") {
            return __("common.normal");
        } elseif($string == "hidden") {
            return __("common.hidden");
        }

        return __("common.normal");
    }

    /**
     * m1 cjx 2020/01/02
     * 获取成功与失败的值
     * @param int $status 0失败 1成功
     * @return string
     */
    public function getSuccessOrFail($status)
    {
        $text = $status == 1 ? __("common.success") : __("common.fail");
        return $text;
    }

    /**
     * m2 cjx 2020/01/02
     * 获取支付宝与微信
     * @param int $status 1支付宝 2招商银行
     * @return string
     */
    public function getAlipayOrWechat($status)
    {
        $text = $status == 1 ? __("common.alipay") : __("common.cmb");
        return $text;
    }

    /**
     * m3 cjx 2020/03/18
     * 快递名称转换
     * @param int $delivery 快递简称
     * @return string
     */
    public function changeDelivery($delivery)
    {
        $text = __('common')[$delivery];
        return $text;
    }


    /**
     * m4 liujh 2020/04/07
     * 获取快递类型
     * @param int $delivery_type 状态值 1标准快递 2商家配送 3自取
     * @return string
     */
    public function getExpressType($express_type)
    {
        if ($express_type == "1") {
            return __("common.standard_express");
        } elseif($express_type == "2") {
            return __("common.mch_delivery");
        }elseif($express_type=="3"){
            return __("common.self_taking");
        }
        return __("common.standard_express");
    }

    /**
     * m5 liujh 2020/04/07
     * 获取邮件连接类型的值
     * @param int $connecttype 连接状态值 1:ssl 2:tls
     * @return string
     */
    public function getEmailConnectType($connecttype)
    {
        $text = $connecttype == 1 ? __("common.ssl") : __("common.tls");
        return $text;
    }

    /**
     * m6 liujh 2020/04/07
     * 获取邮件使用场景
     * @param int $scene 状态值 1内部服务 2服务器报警 3客户邮件
     * @return string
     */
    public function getEmailScene($scene)
    {
        if ($scene == "1") {
            return __("common.internal_service");
        } elseif($scene == "2") {
            return __("common.server_alarm");
        }elseif($scene=="3"){
            return __("common.customer_email");
        }
        return __("common.internal_service");
    }

    /**
     * m7 cjx 2020/04/14
     * 订单生产状态转换
     * @param int $order_prod_status 状态值
     * @return string
     */

    public function exchangeProduction($order_prod_status)
    {
        $arr = [
            ORDER_NO_PRODUCE    =>  '未生产',
            ORDER_PRODUCED      =>  '生产完成',
            ORDER_PRODUCING     =>  '生产中',
        ];
        return $arr[$order_prod_status];
    }

    /**
     * m8 cjx 2020/04/14
     * 订单总状态转换
     * @param int $order_status 状态值
     * @return string
     */

    public function exchangeOrderStatus($order_status)
    {
        $arr = [
            ORDER_STATUS_WAIT_CONFIRM           =>          '待确认',
            ORDER_STATUS_WAIT_PAY               =>          '待付款，已确认',
            ORDER_STATUS_WAIT_PRODUCE           =>          '待生产，已付款',
            ORDER_STATUS_WAIT_DELIVERY          =>          '待发货，已生产',
            ORDER_STATUS_WAIT_RECEIVE           =>          '待收货，已发货',
            ORDER_STATUS_CANCEL                 =>          '交易取消',
            ORDER_STATUS_AFTERSALE              =>          '售后',
            ORDER_STATUS_FINISH                 =>          '交易完成，已收货',
        ];
        return $arr[$order_status];
    }

    /**
     * m9 cjx 2020/04/20
     * 订单支付状态转换
     * @param int $order_prod_status 状态值
     * @return string
     */
    public function exchangePayStatus($order_pay_status)
    {
        $arr = [
            ORDER_UNPAY                 =>          '未付款',
            ORDER_PAYING                =>          '付款中',
            ORDER_PAYED                 =>          '已付款',
        ];
        return $arr[$order_pay_status];
    }

    /**
     * m10 cjx 2020/04/20
     * 订单确认状态转换
     * @param int $order_prod_status 状态值
     * @return string
     */
    public function exchangeConfirmStatus($order_comf_status)
    {
        $arr = [
            ORDER_UNCONFIRMED              =>          '未确认',
            ORDER_CONFIRMED                =>          '已确认',
            ORDER_CANCELED                 =>          '已取消',
            ORDER_INVALID                  =>          '无效',
        ];
        return $arr[$order_comf_status];
    }

    /**
     * m11 cjx 2020/04/20
     * 订单发货状态转换
     * @param int $order_prod_status 状态值
     * @return string
     */
    public function exchangeDeliveryStatus($order_shipping_status)
    {
        $arr = [
            ORDER_UNSHIPPED              =>          '未发货',
            ORDER_SHIPPED                =>          '已发货',
            ORDER_RECEIVED               =>          '已收货',
            ORDER_PREPARING              =>          '备货中',
        ];
        return $arr[$order_shipping_status];
    }

    /**
     * m12 cjx 2020/04/21
     * 发票信息转换
     * @param int $invoice_info 发票信息字段 $type 字段类型区分
     * @return string
     */
    public function exchangeInvoice($invoice_info,$type)
    {
        $str = '';
        if($type == 'inv_type'){
            $str = $invoice_info  == INVOICE_TYPE_ELECTRONICS ? __("common.inv_electronics") : __("common.inv_paper");
        }elseif($type == 'inv_info'){
            $str = $invoice_info  == INVOICE_INFO_DETAIL ? __("common.inv_detail") : __("common.inv_classification");
        }else{
            $str = $invoice_info  == INVOICE_RISE_PERSON ? __("common.inv_person") : __("common.inv_company");
        }

        return $str;
    }

    /**
     * m13 cjx 2020/04/23
     * 售后类型转换
     * @param int $status
     * @return string
     */
    public function exchangeService($status)
    {
        return $status == ORDER_AFTER_TYPE_REFUND ? __("common.refund") : __("common.return_refund");
    }

    /**
     * m14 cjx 2020/04/23
     * 处理、未处理状态转换
     * @param int $status
     * @return string
     */
    public function exchangeHandel($status)
    {
        if ($status == ORDER_AFTER_STATUS_UNPROCESSED){
            return __("common.unprocessed");
        }elseif ($status == ORDER_AFTER_STATUS_PROCESSED) {
            return __("common.processed");
        }elseif ($status == ORDER_AFTER_STATUS_WITHDRAW){
            return __("common.withdraw");
        }else{
            return __("common.review");
        }
    }

    /**
     * m15 cjx 2020/04/25
     * 售后处理方式转换
     * @param int $status
     * @return string
     */
    public function exchangeHandle($status)
    {
        if(!empty($status)){
            $arr = config('order.service_handle_type');
            return $arr[$status];
        }
    }

    /**
     * m16 cjx 2020/05/02
     * 资金明细类型转换
     * @param int $status
     * @return string
     */
    public function fundTypeExchange($status)
    {
        $arr = config('finance.finance_fund_type');
        return $arr[$status];
    }

    /**
     * m17 cjx 2020/05/02
     * 资金类型转换
     * @param int $status
     * @return string
     */
    public function fundChangeExchange($status)
    {
        $arr = config('finance.finance_fund_change_type');
        return $arr[$status];
    }

    /**
     * m18 cjx 2020/07/03
     * 供货商订单状态转换
     * @param int $status
     * @return string
     */
    public function spOrderStatusExchange($status)
    {
        $arr = config('order.supplier_order_status');
        return $arr[$status];
    }
}