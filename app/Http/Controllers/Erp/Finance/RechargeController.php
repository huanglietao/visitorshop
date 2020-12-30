<?php
namespace App\Http\Controllers\Erp\Finance;

use App\Exceptions\CommonException;
use App\Http\Controllers\BaseController;
use App\Services\Helper;
use App\Services\Outer\Erp\Api;
use App\Services\Outer\Pay\CmbAggregate;
use Illuminate\Http\Request;
use App\Services\alipay\AlipayNotify;
use App\Services\alipay\Create;
use Illuminate\Support\Facades\DB;



/**
 * ERP系统账户充值
 *
 * 功能详细说明
 * @author: yanxs <541139655@qq.com>
 * @version: 1.0
 * @date: 2019/10/12
 */
class RechargeController extends BaseController
{
    protected $aliapy_poundage; //支付宝手续费
    protected $zhjh_poundage;   //招行手续费

    public function __construct()
    {
        $this->aliapy_poundage = config('erp.alipay');
        $this->zhjh_poundage = config('erp.zhjh');

    }

    public function index()
    {
//        //指定账号使用招行聚合支付
//        $show_cmb = 0;
//        $allow_user = ['0000000101','0000002482','0000002554','0000002483','0000002481'];
//        if(in_array(session('capital')['data']['partner_code'],$allow_user)){
//            $show_cmb = 1;
//        }
        $show_cmb = 1;

        return view('erp.finance.recharge.index',['aliapy_poundage'=>$this->aliapy_poundage,'zhjh_poundage'=>$this->zhjh_poundage,'show_cmb'=>$show_cmb]);
    }

    public function pay(Request $request)
    {

        //判断是否登录
        if (!session("capital")) {
            //未登录，返回登陆页面
            return redirect('login/index');
        }

        $userinfo = session("capital");

        $amounts = $request->get('amount');

        $type = $request->get('type');

        //金额验证
        if(is_null($request->get('amount')) || $request->get('amount') <= 0 )
        {
            echo "充值金额不能小于等于0";
            die;
        }

        if ($request->get('note'))
        {
            $note = $request->get('note');
        }else{
            $note = "";
        }

        $trade_no = $this->setTradeNo();

        if($type == 1)
        {
            //支付宝

            //计算手续费
            $amounts = round($amounts, 2);
            $handling_fee = round($amounts*$this->aliapy_poundage, 2);

            $out_trade_no = $trade_no;

            $rec_data = [];

            $alipay_config = config('common.alipay_agt');

            //支付宝旧接口测试
            $alipayOld = new Create($alipay_config);

            //需支付的金额
            $now_amount = round($amounts+$handling_fee,2);

            $parameter = array(
                "service" => $alipay_config['service'],
                "partner" => $alipay_config['partner'],
                "seller_id" => $alipay_config['seller_id'],
                "payment_type" => $alipay_config['payment_type'],
                "notify_url" => $alipay_config['fin_notify_url'],
                "return_url" => $alipay_config['fin_return_url'],
                "anti_phishing_key" => $alipay_config['anti_phishing_key'],
                "exter_invoke_ip" => $alipay_config['exter_invoke_ip'],
                "out_trade_no" => $out_trade_no,
                "subject" => '商户充值',
                "total_fee" => $now_amount,
                "body" => '',
                "_input_charset" => trim(strtolower($alipay_config['input_charset']))
            );

            //建立请求
            $html_text = $alipayOld->buildRequestForm($parameter, "get", "确认");



            if ($html_text){
                //插入充值日志表
                $data = [
                    'partner_code'     => $userinfo['data']['partner_code'],
                    'patner_real_name' => $userinfo['data']['partner_real_name'],
                    'partner_name'     => $userinfo['data']['partner_name'],
                    'recharge_no'      => $out_trade_no,
                    'amount'           => $amounts,
                    'handling_fee'     => $handling_fee,
                    'recharge_fee'     => $now_amount,
                    'pay_type'         => $type,
                    'status'           => 0,
                    'note'             => $note,
                    'createtime'       => time(),
                ];
                DB::table("erp_finance_doc")->insert($data);
            }

            echo $html_text;

        }else if($type == 2){

            //招行聚合支付

            //计算手续费
            $amounts = round($amounts, 2);
            $handling_fee = round($amounts*$this->zhjh_poundage, 2);


            //需支付的金额
            $now_amount = round($amounts+$handling_fee,2);

            $out_trade_no = "CMB".$trade_no;

            //插入充值日志表
            $data = [
                'partner_code'     => $userinfo['data']['partner_code'],
                'patner_real_name' => $userinfo['data']['partner_real_name'],
                'partner_name'     => $userinfo['data']['partner_name'],
                'recharge_no'      => $out_trade_no,
                'amount'           => $amounts,
                'handling_fee'     => $handling_fee,
                'recharge_fee'     => $now_amount,
                'pay_type'         => $type,
                'status'           => 0,
                'note'             => $note,
                'createtime'       => time(),
            ];


            DB::table("erp_finance_doc")->insert($data);


            $client = new CmbAggregate();

            //$url = 'https://api.cmburl.cn:8065/polypay/v1.0/mchorders/qrcodeapply';
            $url = 'https://api.cmbchina.com/polypay/v1.0/mchorders/qrcodeapply';

            $data = [
                'biz_content'       => json_encode([
                    'orderId'       => $out_trade_no,
                    'notifyUrl'     =>  config('common.cmb.cmb_notify'),
                    'merId'         =>  config('common.cmb.mch_id'),
                    'userId'        =>  config('common.cmb.user_id'),
                    'txnAmt'        =>  (string)($now_amount*100),    //单位为分
                    'mchReserved'   =>  1,
                    'tradeScene'    => 'OFFLINE',
                ]),
                //'sign'    => '',
                'version'     => '0.0.1',
                'encoding'    => 'UTF-8',
                'signMethod'  => '01',
                //'tradeScene'  => 'OFFLINE'
            ];

            $data = $client->request($url,$data);


            if($data['returnCode'] == 'SUCCESS')
            {
                $biz_content = json_decode($data['biz_content'],true);
                return view('erp.finance.recharge.cmbpay',['amount'=>$now_amount,'qr_code'=>$biz_content['qrCode'],'order_no'=>$out_trade_no]);
            }
            exit;

        }

    }

    /**
     * 支付宝支付异步通知返回
     */
    public function alipaynotify(){


        //计算得出通知验证结果
        $alipay_config = config('common.alipay_agt');
        //file_put_contents('/data/tmp/1.log', var_export($_POST,true));

        $alipayNotify = new AlipayNotify($alipay_config);
        $verify_result = $alipayNotify->verifyNotify();

        if($verify_result) {//验证成功
            /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            //商户订单号
            $out_trade_no = $_POST['out_trade_no'];
            if(stripos($out_trade_no,'recharge') !== false)
            {
                $tradenoArray = explode('recharge',$out_trade_no);
                $recharge_no  = isset($tradenoArray[1]) ? $tradenoArray[1] : 0;

            }
            //支付宝交易号

            $trade_no = $_POST['trade_no'];

            //交易状态
            $trade_status = $_POST['trade_status'];

            //写支付日志

            //file_put_contents($PAYLOG_PATH.$recharge_no.'-'.$trade_no.'-'.date('YmdHis').'.log', var_export($_POST,true));
            //Log::write(var_export($_REQUEST,true),$type = 'log', $force = true);
            if($_POST['trade_status'] == 'TRADE_FINISHED') {
                $this->_doNotify($_POST);
                //判断该笔订单是否在商户网站中已经做过处理
                //如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
                //请务必判断请求时的total_fee、seller_id与通知时获取的total_fee、seller_id为一致的
                //如果有做过处理，不执行商户的业务程序

                //注意：
                //退款日期超过可退款期限后（如三个月可退款），支付宝系统发送该交易状态通知

                //调试用，写文本函数记录程序运行情况是否正常
                //logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
            }
            else if ($_POST['trade_status'] == 'TRADE_SUCCESS') {
                $this->_doNotify($_POST);
                //判断该笔订单是否在商户网站中已经做过处理
                //如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
                //请务必判断请求时的total_fee、seller_id与通知时获取的total_fee、seller_id为一致的
                //如果有做过处理，不执行商户的业务程序

                //注意：
                //付款完成后，支付宝系统发送该交易状态通知

                //调试用，写文本函数记录程序运行情况是否正常
                //logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
            }

            //——请根据您的业务逻辑来编写程序（以上代码仅作参考）——
            echo "success";		//请不要修改或删除

            /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

        }
        else {

            //验证失败
            echo "fail";
            //file_put_contents(PAYLOG_ERROR_PATH.'/alipay_error.log', date('YmdHis').':fail',FILE_APPEND);
            //调试用，写文本函数记录程序运行情况是否正常
            //logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
        }
    }

    private function _doNotify($data)
    {

        $name_date = date('YmdHis');

        //商户订单号
        $out_trade_no = $data['out_trade_no'];
        if (stripos($out_trade_no, 'recharge') !== false) {
            $tradenoArray = explode('recharge', $out_trade_no);
            $recharge_no = isset($tradenoArray[1]) ? $tradenoArray[1] : 0;

        }
        //支付宝交易号
        $trade_no = $data['trade_no'];
        //交易状态
        $trade_status = $data['trade_status'];

        $total_fee = floatval($data['total_fee']);

        //1、查找支付单号是否存在
        $payment_info=json_decode(DB::table("erp_finance_doc")->where(['recharge_no' => $out_trade_no])->get(),true);

        DB::beginTransaction();
        try
        {
            file_put_contents('/tmp/auto_entry_account_error.log','ddd',FILE_APPEND);
            if ($payment_info) {
                //判断支付单未支付

                if ($payment_info[0]['status'] != '1') {
                    //金额匹配

                    if (floatval($total_fee) != floatval($payment_info[0]['recharge_fee'])) {

                        //Log::write(var_export($data,true),$type = 'log', $force = true);
                        //file_put_contents(PAYLOG_ERROR_PATH . $out_trade_no . '-' . $trade_no . '-.log', $name_date . '金额不匹配(' . $total_fee . '---' . floatval($payment_info->account) . ')' . "\t\n", FILE_APPEND);
                        die('success1');
                    }
                    //更新支付单状态
                    //事务开始
                    $update_result = DB::table("erp_finance_doc") ->where(['recharge_no'=>$out_trade_no])->update(['status' => '1', 'trade_no' => $trade_no,'finishtime' => time()]);
                    if (!$update_result) {

                        //file_put_contents(PAYLOG_ERROR_PATH . $recharge_no . '-' . $trade_no . '-.log', $name_date . 'payment update 失败' . "\t\n", FILE_APPEND);
                        //Log::write($recharge_no . '-' . $trade_no , $name_date . 'RechargeDoc update 失败',$type = 'log', $force = true);
                        throw new CommonException("success2");
                    }


                    //访问商户充值接口 by hlt
                    $curl = new Api();
                    $api_url = config("erp.interface_url").config("erp.login");
                    $post_data = [
                        'partner_code' => $payment_info[0]['partner_code'],
                        'money' => floatval($payment_info[0]['amount']),
                        'real_pay_money'   => floatval($payment_info[0]['recharge_fee']),
                        'service_money'   => floatval($payment_info[0]['handling_fee']),
                        'gather_type' => 1,
                        'gather_bank_code' => 1306,
                        'trade_serial_number' => $trade_no,
                        'trade_order_number' => $out_trade_no,
                        'trade_start_time' => date('Y-m-d H:i:s',$payment_info[0]['createtime']),
                        'trade_done_time' => date('Y-m-d H:i:s',time())
                    ];
                    file_put_contents('/tmp/auto_entry_account.log',var_export($post_data,true),FILE_APPEND);
                    $res_arr = new Api();
                    $result_arr  = $res_arr->request(config('erp.interface_url').config('erp.recharge'),$post_data);
                    file_put_contents('/tmp/auto_entry_account.log',var_export($result_arr,true),FILE_APPEND);
                    if($result_arr['code'] == 1){
                        //上账成功
                        DB::table("erp_finance_doc") ->where(['recharge_no'=>$out_trade_no])->update(['capital_change_status' => '1', 'capital_change_msg' => $result_arr['message']]);
                    }else{
                        //上账失败
                        DB::table("erp_finance_doc") ->where(['recharge_no'=>$out_trade_no])->update(['capital_change_status' => '0', 'capital_change_msg' => $result_arr['message']]);
                    }
                    DB::commit();
                   /* $this->success();*/

                }
            } else {
                //如果支付单号不存在，邮件通知技术员
                /*$this->error();*/
            }

        }
        catch (CommonException $e)
        {
            file_put_contents('/tmp/auto_entry_account_error.log',var_export($e->getMessage(),true),FILE_APPEND);
            DB::rollBack();
            /*$this->error($e->getMessage());*/
        }

    }


    /** 生成交易流水号
     *
     * @return string $TradeNo
     *
     */
    public function setTradeNo(){
        $trade_no = '02'.date('ymdHis').rand(100,999);

        $rows = collect(DB::table("erp_finance_doc")->where(['recharge_no' => $trade_no])->get())->toArray();

        if(!empty($rows))
        {
            return self::setTradeNo();
        }

        return  $trade_no;


    }
    //生成签名
    public function sign_md5($params , $key)
    {
        ksort($params);

        $ret_str = '';
        foreach ($params as $k=>$v) {
            $ret_str .= $k.'='.$v."&";
        }
        $ret_str = $ret_str.'key='.$key;
        return strtoupper(md5($ret_str));
    }

    //成功返回界面
    /**
     * 支付成功的返回
     */
    public  function alipayreturn()
    {
        return view("erp.finance.recharge.alipayreturn", [
            'message'  => "充值成功",
            'jumpTime' => "3",
            'url'      => "/#finance/record",
            'jumpText' => "余额",
        ]);
    }


    //上账错误订单重推
    public function retry(Request $request)
    {
        $orderId = $request->route('id');



        $tryArray = collect(DB::table("erp_finance_doc")->where(['id' => $orderId])->get())->toArray();




        /*$tryArray = json_decode(json_encode($tryArray[0]),true);*/
        $tryArray = $tryArray[0];


        if ($tryArray->capital_change_status == '1')
        {
            echo "该订单无须重推";
            die;
        }else{
            //访问商户充值接口 by hlt
            $curl = new Api();
            $api_url = config("erp.interface_url").config("erp.login");
            $post_data = [
                'partner_code' => $tryArray->partner_code,
                'money' => $tryArray->amount,
                'gather_type' => 1,
                'gather_bank_code' => 1306,
                'trade_serial_number' => $tryArray->recharge_no,
                'trade_order_number' => $tryArray->trade_no,
                'trade_start_time' =>date('Y-m-d H:i:s', $tryArray->createtime),
                'trade_done_time' => date('Y-m-d H:i:s',$tryArray->finishtime)
            ];
            file_put_contents('/tmp/auto_entry_account.log',var_export($post_data,true),FILE_APPEND);
            $res_arr = new Api();
            $result_arr  = $res_arr->request(config('erp.interface_url').config('erp.recharge'),$post_data);
            file_put_contents('/tmp/auto_entry_account.log',var_export($result_arr,true),FILE_APPEND);
            if($result_arr['code'] == 1){
                //上账成功
                DB::table("erp_finance_doc") ->where(['id' => $orderId])->update(['capital_change_status' => '1', 'capital_change_msg' => $result_arr['message']]);
            }else{
                //上账失败
                DB::table("erp_finance_doc") ->where(['id' => $orderId])->update(['capital_change_status' => '0', 'capital_change_msg' => $result_arr['message']]);
            }
            echo "重推成功";
            die;

        }


    }

    //招行支付页面轮询通知
    public function checkreturn(Request $request)
    {
        $order_no = $request->post("order_no");

        if(empty($order_no)){
            exit;
        }

        $result = DB::table("erp_finance_doc")->where(['recharge_no'=>$order_no])->first();

        $result = json_decode(json_encode($result), true);

        if(!empty($result)){
            if($result['status'] == 1){

                return Helper::returnJsonSuccess(['status' => '1']);

            }else{
                //请求招行支付查询接口

                $url = 'https://api.cmbchina.com/polypay/v1.0/mchorders/orderquery';
//                $url = 'https://api.cmburl.cn:8065/polypay/v1.0/mchorders/orderquery';

                $client = new CmbAggregate();

                $data = [
                    'biz_content'       => json_encode([
                        'orderId'       => $order_no,
                        'merId'         => config('common.cmb.mch_id'),
                        'userId'        => config('common.cmb.user_id'),
                    ]),
                    'version'       => '0.0.1',
                    'encoding'      => 'UTF-8',
                    'signMethod'    => '01',
                ];

                $data = $client->request($url,$data);

                if($data['respCode'] == 'SUCCESS'){
                    $biz_content = json_decode($data['biz_content'],true);
                    if($biz_content['tradeState'] == 'S'){
                        return Helper::returnJsonSuccess(['status' => '1']);
                    }
                }
            }
        }
        exit;

    }
}
