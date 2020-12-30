<?php
namespace App\Http\Controllers\Agent\Finance;

use App\Exceptions\CommonException;
use App\Http\Controllers\Agent\BaseController;
use App\Repositories\DmsFinanceDocRepository;
use App\Repositories\OmsAgentRechargeRuleRepository;
use Illuminate\Http\Request;

/**
 * 账户充值(表格)
 *
 * @author: cjx <781714246@qq.com>
 * @version: 1.0
 * @date: 2019/8/22
 */

class AccountRechargeController extends BaseController
{

    protected $viewPath = 'agent.finance.accountrecharge';  //当前控制器所的view所在的目录
    protected $modules = 'sys';        //当前控制器所属模块

    public function __construct(DmsFinanceDocRepository $dmsFinanceDocRepository,OmsAgentRechargeRuleRepository $omsAgentRechargeRuleRepository)
    {
        $this->repositories = $dmsFinanceDocRepository;
        $this->omsAgentRechargeRuleRepository = $omsAgentRechargeRuleRepository;
        $this->mch_id = session("admin")['mch_id'];
        $this->agent_id = isset(session('admin')['agent_info_id']) ? session('admin')['agent_info_id'] : '';
    }


    //ajax方式获取列表
    public function table(Request $request)
    {
        try{
            $inputs = $request->all();
            if(isset($inputs['capital_change_status']) && $inputs['capital_change_status']=='0'){
                $inputs['capital_change_status'] = 0;
            }
            $inputs['agent_id'] = $this->agent_id;
            $list = $this->repositories->getTableList($inputs,'finance_doc_id desc');
            $list = $list->toArray();
            foreach ($list['data'] as $k =>$v){
                if(!empty($list['data'][$k]['created_at'])){
                    $list['data'][$k]['created_at'] = (int)$v['created_at'];
                }
                if(!empty($list['data'][$k]['finishtime'])){
                    $list['data'][$k]['finishtime'] = (int)$v['finishtime'];
                }
            }
//            var_dump($list->toArray());exit;
            $htmlContents = $this->renderHtml('agent.finance.accountrecharge._table',['list'=>$list['data']]);
//            $pagesInfo = $list->toArray();
            $total = $list['total'];
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
                $row = $this->repositories->getById($request->input('id'))->toArray();

                if(!empty($row['created_at'])){
                    $row['created_at'] = (int)$row['created_at'];
                }
                if(!empty($row['finishtime'])){
                    $row['finishtime'] = (int)$row['finishtime'];
                }
                $row['rule_name'] = "暂无参加活动";
                if(!empty($row['rec_rule_id'])){
                    $rule = $this->omsAgentRechargeRuleRepository->getById($row['rec_rule_id'])->toArray();
                    $row['rule_name'] = $rule['rec_rule_name'];
                }


                $htmlContents = $this->renderHtml($this->viewPath.'.'.$this->form, ['row' => $row]);
                return $this->jsonSuccess(['html' => $htmlContents]);
            }else{
                return view("admin.tips");
            }
        } catch (CommonException $e) {
            return $this->jsonFailed($e->getMessage());
        }

    }


//    //详情
//    public function info(Request $request)
//    {
//        if($request->ajax())
//        {
//            $htmlContents = $this->renderHtml("agent.finance.accountrecharge.info");
//            return $this->jsonSuccess(['status' => 200, 'html' => $htmlContents]);
//        }else{
//            return view("agent.finance.accountrecharge.info");
//        }
//    }
//
//    //取消
//    public function cancel(Request $request)
//    {
//        if($request->ajax())
//        {
//            $htmlContents = $this->renderHtml("agent.finance.accountrecharge.cancel");
//            return $this->jsonSuccess(['status' => 200, 'html' => $htmlContents]);
//        }else{
//            return view("agent.finance.accountrecharge.cancel");
//        }
//    }
}