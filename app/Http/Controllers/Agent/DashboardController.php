<?php
namespace App\Http\Controllers\Agent;

use App\Repositories\AgentRepository;


/**
 * 控制台
 *
 * 分销基本信息及统计相关的信息
 * @author: yanxs <541139655@qq.com>
 * @version: 1.0
 * @date: 2019/7/31
 */

class DashboardController extends BaseController
{

    protected $agentRepository;

    public function __construct(AgentRepository $agentRepository)
    {
        $this->agentRepository = $agentRepository;
        $this->mch_id = session("admin")['mch_id'];
        $this->agent_id = isset(session('admin')['agent_info_id']) ? session('admin')['agent_info_id'] : '';
        $this->agent_admin_id = isset(session('admin')['dms_adm_id']) ? session('admin')['dms_adm_id'] : '';
    }

    public function index()
    {
        //账户信息、公告
        $account_info = $this->agentRepository->getAccountInfo($this->agent_admin_id);

        //订单销售数据
        $sales_info = $this->agentRepository->getSalesInfo($this->agent_id,$this->mch_id);

        return view('agent.dashboard.index',['account_info'=>$account_info,'sales_info'=>$sales_info]);
    }

    //获取控制台图表数据
    public function getChartData()
    {
        $data = $this->agentRepository->getChartData($this->agent_id,$this->mch_id);
        return $this->jsonSuccess($data);
    }

}