<?php
namespace App\Http\Controllers\Agent\Finance;

use App\Exceptions\CommonException;
use App\Http\Controllers\Agent\BaseController;
use App\Repositories\DmsAgentInfoRepository;
use App\Repositories\DmsFinanceDocRepository;
use App\Repositories\DmsMerchantAccountRepository;
use App\Repositories\OmsAgentRechargeRuleRepository;
use App\Repositories\SaasCustomerBalanceLogRepository;
use App\Repositories\SaasPaymentRepository;
use App\Services\alipay\AlipayNotify;
use App\Services\alipay\Create;
use Illuminate\Http\Request;
use Yansongda\LaravelPay\Facades\Pay;

/**
 * 账户充值(线下入账、即时到账)
 *
 * @author: cjx <781714246@qq.com>
 * @version: 1.0
 * @date: 2019/8/5
 */

class RechargeController extends BaseController
{
    protected $viewPath = 'agent.finance.recharge';  //当前控制器所的view所在的目录
    protected $modules = 'sys';        //当前控制器所属模块
    protected $dmsAgentInfoRepository;
    protected $aliapy_poundage;
    protected $wx_poundage;
    protected $agentInfo;
    protected $noCookie = 'alipaynotify,_doNotify,wxpaynotify,ajax_check_recharge,alipayreturn';   //不需要验证商家id


    public function __construct(DmsAgentInfoRepository $dmsAgentInfoRepository,DmsFinanceDocRepository $dmsFinanceDocRepository,
                                OmsAgentRechargeRuleRepository $omsAgentRechargeRuleRepository,SaasCustomerBalanceLogRepository $customerBalanceLogRepository,
                                DmsMerchantAccountRepository $dmsMerchantAccountRepository,SaasPaymentRepository $paymentRepository)
    {
        parent::__construct();
        $this->dmsAgentInfoRepository = $dmsAgentInfoRepository;
        $this->dmsFinanceDocRepository = $dmsFinanceDocRepository;
        $this->omsAgentRechargeRuleRepository = $omsAgentRechargeRuleRepository;
        $this->customerBalanceLogRepository = $customerBalanceLogRepository;
        $this->dmsMerchantAccountRepository = $dmsMerchantAccountRepository;
        $this->paymentRepository = $paymentRepository;
        $this->agentInfo = empty(session('admin')) == false ? session('admin') : ' ';
        $this->merchantID = empty(session('admin')) == false ? session('admin')['mch_id'] : ' ';
    }

    public function index()
    {
        //获取商家设置的充值规则
        $rule = [];
        $rule = $this->omsAgentRechargeRuleRepository->getRuleByMid($this->merchantID);
        return view('agent.finance.recharge.index',['rule'=>$rule]);
    }

    public function pay(Request $request)
    {
        $params = $request->all();
//        var_dump($params);exit;
        //奖励金额
        $present_fee = 0;
        $rec_rule_id = 0;
        if(!empty($params['amount'])){
            //充值金额
            $amount = $params['amount'];
        }
        else if(!empty($params['rule_id'])){
            $rec_rule_id = $params['rule_id'];
            $ruleInfo = $this->omsAgentRechargeRuleRepository->getRuleByMid($this->merchantID,$params['rule_id']);
            $amount = $ruleInfo['recharge_fee'];
            $present_fee = $ruleInfo['present_fee'];
        }

        //选择的支付类型1:支付宝,2:微信
        $type = $params['type'];
        //支付备注
        if ($params['note'])
        {
            $note = $params['note'];
        }else{
            $note = "";
        }
        //交易流水号
        $trade_no = $this->setTradeNo();
        //支付宝
        if($type == 1)
        {
            //交易流水号
            $out_trade_no = $trade_no;
            //获取支付宝配置信息
            $alipay_config = config('common.alipay_agt');
            //支付宝旧接口测试
            $alipayOld = new Create($alipay_config);
            //需支付的金额（充值金额加上手续费）
            $recharge_fee = round($amount,2);
            //参数配置
            $parameter = array(
                "service" => $alipay_config['service'],
                "partner" => $alipay_config['partner'],
                "seller_id" => $alipay_config['seller_id'],
                "payment_type" => $alipay_config['payment_type'],
                "notify_url" => "http://".config('app.agent_url')."/finance/recharge/alipaynotify",
                "return_url" => "http://".config('app.agent_url')."/finance/recharge/alipayreturn",
                "anti_phishing_key" => $alipay_config['anti_phishing_key'],
                "exter_invoke_ip" => $alipay_config['exter_invoke_ip'],
                "out_trade_no" => $out_trade_no,
                "subject" => '商户充值',
                "total_fee" => $recharge_fee,
                "body" => '',
                "_input_charset" => trim(strtolower($alipay_config['input_charset']))
            );

            //建立请求
            $html_text = $alipayOld->buildRequestForm($parameter, "get", "确认");

            if ($html_text){
                //插入充值日志表
                $data = [
                    'mch_id'            => $this->merchantID,
                    'agent_id'          => $this->agentInfo['agent_info_id'],
                    'rec_rule_id'       => $rec_rule_id,
                    'partner_real_name' => $this->agentInfo['dms_adm_username'],
                    'partner_name'     => $this->agentInfo['dms_adm_nickname'],
                    'recharge_no'      => $out_trade_no,
                    'amount'           => $amount,
                    'present_fee'      => $present_fee,
                    'recharge_fee'     => $recharge_fee,
                    'pay_type'         => $type,
                    'recharge_type'    => 2,
                    'status'           => 0,
                    'note'             => $note,
                ];
                $ret = $this->dmsFinanceDocRepository->save($data);
                if($ret){
                    echo $html_text;
                }
            }

        }else if($type == 2){

            //需支付的金额
            $recharge_fee = round($amount,2);
            //交易流水号
            $out_trade_no = $trade_no;
            //构建订单信息
            $order = [
                'out_trade_no' =>$out_trade_no,//你的订单号
                'body'         => '商户充值-账户充值',
                'total_fee'    => $recharge_fee*100,//单位为分
                'spbill_create_ip' => config('common.spbill_create_ip'),
                //'attach'   =>$this->mid,
            ];
            //创建微信订单
            $pay = Pay::wechat()->scan($order);
            //存入数据表的数据
            $data = [
                'mch_id'            => $this->merchantID,
                'agent_id'          => $this->agentInfo['agent_info_id'],
                'rec_rule_id'       => $rec_rule_id,
                'partner_real_name' => $this->agentInfo['dms_adm_username'],
                'partner_name'     => $this->agentInfo['dms_adm_nickname'],
                'recharge_no'      => $out_trade_no,
                'amount'           => $amount,
                'present_fee'      => $present_fee,
                'recharge_fee'     => $recharge_fee,
                'pay_type'         => $type,
                'recharge_type'    => 2,
                'status'           => 0,
                'note'             => $note,
            ];
            $ret = $this->dmsFinanceDocRepository->save($data);
            if($ret){
                return view('agent.finance.recharge.wxpay',['amount'=>$recharge_fee,'qr_code'=>$pay['code_url'],'order_no'=>$out_trade_no]);
            }
        }

        echo "<span style='text-align: center'><h1>出错了，请联系客服</h1></span>";
    }

    //线下入账
    public function offlinePay(Request $request)
    {
        $params = $request->all();
        //奖励金额
        $present_fee = 0;
        $rec_rule_id = 0;
        if(!empty($params['amount'])){
            //充值金额
            $amount = $params['amount'];
        }
        else if(!empty($params['rule_id'])){
            $rec_rule_id = $params['rule_id'];
            $ruleInfo = $this->omsAgentRechargeRuleRepository->getRuleByMid($this->merchantID,$params['rule_id']);
            $amount = $ruleInfo['recharge_fee'];
            $present_fee = $ruleInfo['present_fee'];
        }

        //转账凭证照片
        $images = $params['images'];

        //选择的支付类型1:支付宝,2:微信
        $type = $params['pay_type'];
        //支付备注
        if ($params['note'])
        {
            $note = $params['note'];
        }else{
            $note = "";
        }
        //第三方交易流水号
        $trade_no = $params['trade_no'];
        //交易流水号
        $recharge_no = $this->setTradeNo();

        //支付金额
        $recharge_fee = $amount;

        \DB::beginTransaction();
        try{
            //存入数据表的数据
            $data = [
                'mch_id'            => $this->merchantID,
                'agent_id'          => $this->agentInfo['agent_info_id'],
                'rec_rule_id'       => $rec_rule_id,
                'partner_real_name' => $this->agentInfo['dms_adm_username'],
                'partner_name'     => $this->agentInfo['dms_adm_nickname'],
                'recharge_no'      => $recharge_no,
                'trade_no'         => $trade_no,
                'amount'           => $amount,
                'present_fee'      => $present_fee,
                'recharge_fee'     => $recharge_fee,
                'pay_type'         => $type,
                'recharge_type'    => 1,
                'status'           => 1,
                'images'           => $images,
                'note'             => $note,
            ];

            $ret = $this->dmsFinanceDocRepository->save($data);
            if($ret){
                \DB::commit();
                return $this->jsonSuccess([]);
            }else{
                \DB::rollBack();
                return $this->jsonFailed("程序出错了");
            }
        }catch (CommonException $e){
            \DB::rollBack();
            return $this->jsonFailed($e->getMessage());
        }
    }

    /** 生成交易流水号
     *
     * @return string $TradeNo
     *
     */
    public function setTradeNo(){
        $trade_no = '02'.date('ymdHis').rand(100,999);
        $rows = $this->dmsFinanceDocRepository->getByRechargeNo($trade_no);
        if(!empty($rows))
        {
            return self::setTradeNo();
        }
        return  $trade_no;
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
        //充值金额，不包含手续费
        $total_fee = floatval($data['total_fee']);

        //1、查找支付单号是否存在
        $payment_info = $this->dmsFinanceDocRepository->getByRechargeNo($out_trade_no);

        \DB::beginTransaction();
        try
        {
//            file_put_contents('/tmp/auto_entry_account_error.log','ddd',FILE_APPEND);
            if ($payment_info) {
                //判断支付单未支付
                if ($payment_info[0]['status'] != '1') {
                    //金额匹配
                    if (floatval($total_fee) != floatval($payment_info[0]['recharge_fee'])) {
                        //Log::write(var_export($data,true),$type = 'log', $force = true);
                        //file_put_contents(PAYLOG_ERROR_PATH . $out_trade_no . '-' . $trade_no . '-.log', $name_date . '金额不匹配(' . $total_fee . '---' . floatval($payment_info->account) . ')' . "\t\n", FILE_APPEND);
                        die('success1');
                    }

                    $agent_id = $payment_info[0]['agent_id'];
                    //查出账号id
                    $account = $this->dmsMerchantAccountRepository->getAgentInfo($agent_id);
                    $account_id = $account['dms_adm_id'];
                    $mch_id = $account['mch_id'];
                    //更新支付单状态
                    //事务开始
                    $updateData = [
                        'status' => '1',
                        'capital_change_status'=>1,
                        'trade_no' => $trade_no,
                        'finishtime' => time()
                    ];
                    $update_result = $this->dmsFinanceDocRepository->updateFinanceDoc($out_trade_no,$updateData);

                    //没有出错的情况将分销店铺的余额更新
                    $agentInfo = $this->dmsAgentInfoRepository->getTableList(['agent_info_id'=>$agent_id])->toArray();
                    $agent_balance = $agentInfo['data'][0]['agent_balance'];

                    //获取支付方式为支付宝的id
                    $payInfo = $this->paymentRepository->getPayName($mch_id,'alipay');

                    //插入资金明细表
                    $balanceLogData = [
                        'mch_id'                     => $mch_id,
                        'user_id'                    => $agent_id,
                        'user_type'                  => CHANEL_TERMINAL_AGENT,
                        'operate_type'               => OPERATE_TYPE_USER,
                        'operate_id'                 => $account_id,
                        'cus_balance_type'           => FINANCE_INCOME,
                        'cus_balance_type_detail'    => FINANCE_CHANGE_TYPE_RECHARGE,
                        'cus_balance_change'         => $total_fee,
                        'cus_balance'                => $agent_balance+$total_fee,
                        'cus_balance_frozen_change'  => ZERO,
                        'cus_balance_frozen'         => ZERO,
                        'cus_balance_business_no'    => $out_trade_no,
                        'cus_balance_trade_no'       => $trade_no,
                        'pay_id'                     => $payInfo[0]['pay_id']??ZERO,
                        'remark'                     => $payment_info[0]['note'],
                        'created_at'                 => time()
                    ];

                    $this->customerBalanceLogRepository->save($balanceLogData);

                    $present_fee = (int)$payment_info[0]['present_fee'];
                    //是否有奖励金额
                    if($present_fee>0){
                        //插入资金明细表
                        $balancePreData = [
                            'mch_id'                     => $mch_id,
                            'user_id'                    => $agent_id,
                            'user_type'                  => CHANEL_TERMINAL_AGENT,
                            'operate_type'               => OPERATE_TYPE_USER,
                            'operate_id'                 => $account_id,
                            'cus_balance_type'           => FINANCE_INCOME,
                            'cus_balance_type_detail'    => FINANCE_CHANGE_TYPE_GIVE,
                            'cus_balance_change'         => $payment_info[0]['present_fee'],
                            'cus_balance'                => $agent_balance+$total_fee+$present_fee,
                            'cus_balance_frozen_change'  => ZERO,
                            'cus_balance_frozen'         => ZERO,
                            'cus_balance_business_no'    => $this->setTradeNo(),
                            'pay_id'                     => ZERO,
                            'remark'                     => '',
                            'created_at'                 => time()
                        ];

                        $this->customerBalanceLogRepository->save($balancePreData);
                    }
                    $agent_now_balance = $agent_balance+$total_fee+$present_fee;
                    $this->dmsAgentInfoRepository->save(['agent_info_id'=>$agent_id,'agent_balance'=>$agent_now_balance]);
                    if (!$update_result) {
                        throw new CommonException("success2");
                    }
                    \DB::commit();
                }
            } else {
                //如果支付单号不存在，邮件通知技术员
                /*$this->error();*/
            }
        }
        catch (CommonException $e)
        {
            file_put_contents('/tmp/auto_entry_account_error.log',var_export($e->getMessage(),true),FILE_APPEND);
            \DB::rollBack();
            /*$this->error($e->getMessage());*/
        }
    }

    //成功返回界面
    /**
     * 支付成功的返回
     */
    public  function alipayreturn()
    {
        return view("agent.finance.recharge.alipayreturn", [
            'message'  => "充值成功",
            'jumpTime' => "3",
            'url'      => "/#finance/recharge/index",
            'jumpText' => "充值",
        ]);
    }


    /**
     * 微信支付异步通知
     */
    public function wxpaynotify()
    {
        //初始化日志
//        $config = config('pay.wechat');
        $pay = Pay::wechat();
//        $data = $pay->verify(); // 是的，验签就这么简单
        if (!$pay) {
            echo '签名错误';
            return;
        }
        //你可以直接通过$pay->verify();获取到相关信息
        //支付宝可以获取到out_trade_no,total_amount等信息
        //微信可以获取到out_trade_no,total_fee等信息
        $data = $pay->verify();

        $out_trade_no = $data['out_trade_no'];

        if(stripos($out_trade_no,'recharge') !== false) {
            $tradenoArray = explode('recharge', $out_trade_no);
            $recharge_no = isset($tradenoArray[1]) ? $tradenoArray[1] : 0;
        }
        //1、查找支付单号是否存在
        $payment_info = $this->dmsFinanceDocRepository->getByRechargeNo($out_trade_no);
        //收款单是否存在
        if ($payment_info) {
            if ($payment_info[0]['status'] == '1') {//已支付
                exit();
            } else {
                //未支付：查询订单，并更新相关操作
                //查询订单，判断订单真实性
                if ($data['result_code'] != 'SUCCESS' && $data['return_code'] != 'SUCCESS'){
                    exit();
                }
                $total_fee = floatval($data['total_fee']) / 100;
                if ($total_fee != floatval($payment_info[0]['amount'])) {
                    exit();
                }
                //更新支付单状态
                //事务开始
                $trade_no = $data['transaction_id'];

                \DB::beginTransaction();
                try {
                    //更新支付单状态
                    //事务开始
                    $updateData = [
                        'status' => '1',
                        'capital_change_status'=>1,
                        'trade_no' => $trade_no,
                        'finishtime' => time()
                    ];
                    $update_result = $this->dmsFinanceDocRepository->updateFinanceDoc($out_trade_no,$updateData);
                    if (!$update_result) {
                        exit();
                    }

                    $agent_id = $payment_info[0]['agent_id'];
                    //查出账号id
                    $account = $this->dmsMerchantAccountRepository->getAgentInfo($agent_id);
                    $account_id = $account['dms_adm_id'];
                    $mch_id = $account['mch_id'];

                    //没有出错的情况将分销店铺的余额更新
                    $agentInfo = $this->dmsAgentInfoRepository->getTableList(['agent_info_id'=>$agent_id])->toArray();
                    $agent_balance = $agentInfo['data'][0]['agent_balance'];

                    //获取支付方式为微信的id
                    $payInfo = $this->paymentRepository->getPayName($mch_id,'wxpay');

                    //插入资金明细表
                    $balanceLogData = [
                        'mch_id'                     => $mch_id,
                        'user_id'                    => $agent_id,
                        'user_type'                  => CHANEL_TERMINAL_AGENT,
                        'operate_type'               => OPERATE_TYPE_USER,
                        'operate_id'                 => $account_id,
                        'cus_balance_type'           => FINANCE_INCOME,
                        'cus_balance_type_detail'    => FINANCE_CHANGE_TYPE_RECHARGE,
                        'cus_balance_change'         => $total_fee,
                        'cus_balance'                => $agent_balance+$total_fee,
                        'cus_balance_frozen_change'  => ZERO,
                        'cus_balance_frozen'         => ZERO,
                        'cus_balance_business_no'    => $out_trade_no,
                        'cus_balance_trade_no'       => $trade_no,
                        'pay_id'                     => $payInfo[0]['pay_id']??ZERO,
                        'remark'                     => $payment_info[0]['note'],
                        'created_at'                 => time()
                    ];

                    $this->customerBalanceLogRepository->save($balanceLogData);

                    $present_fee = (int)$payment_info[0]['present_fee'];
                    //是否有奖励金额
                    if($present_fee>0){
                        //插入资金明细表
                        $balancePreData = [
                            'mch_id'                     => $mch_id,
                            'user_id'                    => $agent_id,
                            'user_type'                  => CHANEL_TERMINAL_AGENT,
                            'operate_type'               => OPERATE_TYPE_USER,
                            'operate_id'                 => $account_id,
                            'cus_balance_type'           => FINANCE_INCOME,
                            'cus_balance_type_detail'    => FINANCE_CHANGE_TYPE_GIVE,
                            'cus_balance_change'         => $payment_info[0]['present_fee'],
                            'cus_balance'                => $agent_balance+$total_fee+$present_fee,
                            'cus_balance_frozen_change'  => ZERO,
                            'cus_balance_frozen'         => ZERO,
                            'cus_balance_business_no'    => $this->setTradeNo(),
                            'pay_id'                     => ZERO,
                            'remark'                     => '',
                            'created_at'                 => time()
                        ];

                        $this->customerBalanceLogRepository->save($balancePreData);
                    }
                    $agent_now_balance = $agent_balance+$total_fee+$present_fee;
                    $this->dmsAgentInfoRepository->save(['agent_info_id'=>$agent_id,'agent_balance'=>$agent_now_balance]);
                    \DB::commit();
                    echo $pay->success();
                    return ;
                } catch (CommonException $e) {
                    \DB::rollback();//事务回滚
//                        Log::write(date("Y-m-d H:i:s")."eee".$e->getMessage());
                    $this->jsonFailed($e->getMessage());
                }
            }
        } else {
            exit();
        }
    }
    /**
     * 微信支付异步通知
     */

    public function ajax_check_recharge(Request $request)
    {
        $request = $request->all();
        $change_doc = $this->dmsFinanceDocRepository->getByRechargeNo($request['order_no']);

        if ($change_doc[0]['status']==1){
            return json_encode(['status' => 200, 'msg' => "支付成功"],JSON_UNESCAPED_UNICODE);
        }else{
            return json_encode(['status' => 201, 'msg' => "等待支付"],JSON_UNESCAPED_UNICODE);
        }
        //你可以在这里定义你的提示信息,但切记不可在此编写逻辑
        //$this->success("恭喜你！支付成功!", addon_url("/finance/recharge/alipayreturn?ref=addtabs"));
    }



}