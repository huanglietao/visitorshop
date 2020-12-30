<?php
namespace App\Http\Controllers\Merchant\Agent;

use App\Exceptions\CommonException;
use App\Http\Controllers\Merchant\BaseController;
use App\Http\Requests\Merchant\Agent\InfoRequest;
use App\Repositories\DmsAgentInfoRepository;
use App\Repositories\DmsFinanceDocRepository;
use App\Repositories\DmsMerchantAccountRepository;
use App\Repositories\SaasCustomerLevelRepository;
use App\Services\Helper;
use Illuminate\Http\Request;

/**
 * 项目说明 OMS系统 分销管理--商家列表
 * 详细说明 OMS系统 分销管理--商家列表，实现列表，添加，编辑，删除及组件结合
 * @author: liujh <vali12138@163.com>
 * @version: 1.0
 * @date:  2020/04/15
 */
class InfoController extends BaseController
{
    protected $viewPath = 'merchant.agent.info';  //当前控制器所的view所在的目录
    protected $modules = 'sys';        //当前控制器所属模块
    protected $merchantID = '';
    protected $GradeRepository = "";


    public function __construct(DmsAgentInfoRepository $Repository,SaasCustomerLevelRepository $GradeRepository)
    {
        parent::__construct();
        $this->repositories = $Repository;
        $this->GradeRepository = $GradeRepository;
        $this->merchantID = empty(session('admin')) == false ? session('admin')['mch_id'] : ' ';
        $this->shop_type = config('agent.shop_type');
        $this->agentInfo = $Repository->getList(['mch_id'=>$this->merchantID])->toArray();
    }


    /**
     * 功能首页结构view
     * @return mixed
     */
    public function index()
    {
        $shop_type = Helper::getChooseSelectData($this->shop_type);
        return view('merchant.agent.info.index',['shop_type'=>$shop_type]);
    }

    /**
     * ajax获取列表项
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function table(Request $request)
    {
        try{
            $inputs = $request->all();
            $inputs['mch_id']=$this->merchantID;
            $list = $this->repositories->getTableList($inputs,"agent_info_id desc");

            $result = $list->toArray();
            //得到所属等级
            foreach ($result['data'] as $k=>$v){
                $result['data'][$k]['cust_lv_name'] = $this->GradeRepository->getGrade($this->merchantID,CHANEL_TERMINAL_AGENT,$v['cust_lv_id']);
            }
            //获取该商家下的所有分销商
            $info = Helper::ListToKV('agent_info_id','agent_name',$this->agentInfo);
            $info = [0=>""]+$info;

            $htmlContents = $this->renderHtml('',['list' =>$result['data'],'shop_type'=>$this->shop_type,'info'=>$info]);
            $pagesInfo = $list->toArray();
            $total = $pagesInfo['total'];
            return $this->jsonSuccess(['html' => $htmlContents,'total' => $total]);
        }catch (CommonException $e){
            $this->jsonFailed($e->getMessage());
        }

    }


    /**
     * 通用表单展示
     * @param Request $request
     * @return mixed
     */
    public function form(Request $request)
    {
        try {
            if($request->ajax())
            {
                $row = $this->repositories->getById($request->input('id'));

                //获取该商家下的所有分销商
                $info = Helper::ListToKV('agent_info_id','agent_name',$this->agentInfo);
                unset($info[$request->input('id')]);
                $info = [0=>'请选择']+$info;

                $grade = $this->GradeRepository->getGrade($this->merchantID,CHANEL_TERMINAL_AGENT);
                $grade = Helper::getChooseSelectData($grade);
                $shop_type = Helper::getChooseSelectData($this->shop_type);
                $htmlContents = $this->renderHtml($this->viewPath.'.'.$this->form, ['row' => $row,'grade'=>$grade,'shop_type'=>$shop_type,'info'=>$info]);
                return $this->jsonSuccess(['html' => $htmlContents]);
            }else{
                return view("admin.tips");
            }
        } catch (CommonException $e) {
            return $this->jsonFailed($e->getMessage());
        }

    }

    //资金变动页面
    public function capital(Request $request)
    {
        $agent_info_id = $request->input('id');
        $agent_info = $this->repositories->getById($agent_info_id)->toArray();
        $htmlContents = $this->renderHtml($this->viewPath.'._capital', ['agent_info'=>$agent_info]);
        return $this->jsonSuccess(['html' => $htmlContents]);
    }

    //资金变动
    public function capital_save(Request $request)
    {
        try{
            \DB::beginTransaction();
            $params = $request->all();
            if($params['new_balance']<0){
                return $this->jsonFailed('充值金额必须大于0');
            }

            if($params['balance_type']==1) {
                $balance_type = FINANCE_INCOME;
                $balance_type_detail = FINANCE_CHANGE_TYPE_RECHARGE;
                $agent_change_balance = $params['agent_balance']+$params['new_balance'];
            }else{
                $balance_type = FINANCE_EXPEND;
                $balance_type_detail = FINANCE_CHANGE_TYPE_TRADE;
                $agent_change_balance = $params['agent_balance']-$params['new_balance'];
                if($params['agent_balance']<$params['new_balance']){
                   return $this->jsonFailed("余额不足，扣款失败！");
                }
            }

            $agent_balance = [
                'agent_info_id'=>$params['agent_info_id'],
                'agent_balance'=>$agent_change_balance
            ];

            $ret = $this->repositories->save($agent_balance);

            if($ret){

                $agentInfoRepository = app(DmsMerchantAccountRepository::class);
                $agentInfo = $agentInfoRepository->getAgentInfo($params['agent_info_id']);
                $out_trade_no = $this->setTradeNo();
                $finance_doc_data = [
                    'mch_id'            => $this->merchantID,
                    'agent_id'          => $params['agent_info_id'],
                    'partner_real_name' => $agentInfo['dms_adm_username'],
                    'partner_name'     => $agentInfo['dms_adm_nickname'],
                    'recharge_no'      => $out_trade_no,
                    'capital_change_status' => ONE,
                    'amount'           => $params['new_balance'],
                    'recharge_fee'     => $params['new_balance'],
                    'recharge_type'    => ONE,
                    'status'           => ONE,
                    'images'            => $params['images'],
                    'note'             => $params['remark'],
                    'finishtime'        => time()
                ];

                $dmsFinanceDocRepository = app(DmsFinanceDocRepository::class);
                $fin_ret = $dmsFinanceDocRepository->save($finance_doc_data);

                $balance_log_data = [
                    'mch_id'=>$this->merchantID,
                    'user_id'=>$params['agent_info_id'],
                    'user_type'=>CHANEL_TERMINAL_AGENT,
                    'operate_type'=>OPERATE_TYPE_ADMIN,
                    'operate_id'=>session('admin')['oms_adm_id'],
                    'cus_balance_type'=>$balance_type,
                    'cus_balance_type_detail'=>$balance_type_detail,
                    'cus_balance_change'=>$params['new_balance'],
                    'cus_balance'=>$agent_change_balance,
                    'cus_balance_business_no' =>$out_trade_no,
                    'remark'=>$params['remark']
                ];
                $cus_ret = $this->repositories->balanceLogSave($balance_log_data);
                if($cus_ret && $fin_ret){
                    \DB::commit();
                    return $this->jsonSuccess([]);
                }
            }
        }catch (CommonException $e){}
            \DB::rollBack();
            return $this->jsonFailed($e->getMessage());

    }


   //添加/编辑操作
    public function save(InfoRequest $request)
    {
        $params = $request->all();
        unset($params['_token']);
        //判断省市区如果有值，是否完整
        if(!empty($params['province'])){
            if(empty($params['district']) || $params['district']=='0' || $params['district']=='-1' || $params['district']=='区'){
                return $this->jsonFailed("请把地区选择完整");
            }
        }
        $ret = $this->repositories->save($params);
        if ($ret) {
            return $this->jsonSuccess([]);
        } else {
            return $this->jsonFailed('');
        }
    }

    /** 生成交易流水号
     *
     * @return string $TradeNo
     *
     */
    public function setTradeNo(){
        $dmsFinanceDocRepository = app(DmsFinanceDocRepository::class);
        $trade_no = '02'.date('ymdHis').rand(100,999);
        $rows = $dmsFinanceDocRepository->getByRechargeNo($trade_no);
        if(!empty($rows))
        {
            return self::setTradeNo();
        }
        return  $trade_no;
    }

}
