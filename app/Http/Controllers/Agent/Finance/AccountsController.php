<?php
namespace App\Http\Controllers\Agent\Finance;

use App\Exceptions\CommonException;
use App\Http\Controllers\Agent\BaseController;
use App\Repositories\DmsAgentInfoRepository;
use App\Repositories\SaasCustomerBalanceLogRepository;
use Illuminate\Http\Request;

/**
 * 资金账务
 *
 * @author: cjx <781714246@qq.com>
 * @version: 1.0
 * @date: 2019/8/5
 */

class AccountsController extends BaseController
{
    protected $viewPath = 'agent.finance.accounts';  //当前控制器所的view所在的目录
    protected $modules = 'sys';        //当前控制器所属模块

    public function __construct(DmsAgentInfoRepository $agentInfoRepository,SaasCustomerBalanceLogRepository $customerBalanceLogRepository)
    {
        $this->mch_id = session("admin")['mch_id'];
        $this->agent_id = isset(session('admin')['agent_info_id']) ? session('admin')['agent_info_id'] : '';
        $this->agentInfoRepository = $agentInfoRepository;
        $this->customerBalanceLogRepository = $customerBalanceLogRepository;
    }

    public function index()
    {
        $agentInfo = $this->agentInfoRepository->getTableList(['agent_info_id'=>$this->agent_id])->toArray();
        $userBalance = $this->customerBalanceLogRepository->getTableList([],'cus_balance_id desc')->toArray();
        //分销资金信息
        $userBalanceInfo = [
            'total_balance'=>$agentInfo['data'][0]['agent_balance']+($userBalance['data'][0]['cus_balance_frozen']??0),
            'now_balance'=>$agentInfo['data'][0]['agent_balance'],
            'frozen_balance'=>$userBalance['data'][0]['cus_balance_frozen']??0,
            'is_remind_status'=>$agentInfo['data'][0]['is_remind_status'],
            'remind_balance'=>$agentInfo['data'][0]['agent_balance_remind']
        ];

        if(\request()->ajax()){
            return $this->jsonSuccess($userBalanceInfo);
        }
        else{
            //资金收入与支出
            $balanceStatus = $this->customerBalanceLogRepository->getBalanceStatus($this->agent_id);
            //最近30天折线图
            $chartInfo = $this->customerBalanceLogRepository->getChartInfo();

            return view('agent.finance.accounts.index',['balanceInfo'=>$userBalanceInfo,'balanceStatus'=>$balanceStatus,'chartInfo'=>json_encode($chartInfo)]);

        }
    }


    //更新分销商的余额提醒阈值和状态
    public function remindStatus(Request $request)
    {
        try{
            $params = $request->all();
            if($params['status']=='true'){
                $status = ONE;
            }else{
                $status = ZERO;
            }
            \DB::beginTransaction();

            $data = [
                'agent_info_id'=>$this->agent_id,
                'is_remind_status'=>$status,
                'agent_balance_remind'=>$params['remind_balance']
            ];

            $ret = $this->agentInfoRepository->save($data);
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


    /**
     * 列表测试
     */
    public function table(Request $request)
    {

        //这里做测试的

        $list = $this->agentRepository->getAccountList();
        $htmlContents = $this->renderHtml('agent.dashboard._table',['list' =>$list]);

        return response()->json(['status' => 200, 'html' => $htmlContents]);

    }
}