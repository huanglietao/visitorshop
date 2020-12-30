<?php
namespace App\Http\Controllers\Merchant\Agent;

use App\Exceptions\CommonException;
use App\Http\Controllers\Merchant\BaseController;
use App\Repositories\DmsAgentInfoRepository;
use App\Repositories\SaasCustomerBalanceLogRepository;
use Illuminate\Http\Request;

/**
 * 资金明细
 * @author: liujh
 * @version: 1.0
 * @date: 2020/6/17
 */

class FundController extends BaseController
{
    protected $viewPath = 'merchant.agent.fund';  //当前控制器所的view所在的目录
    protected $modules = 'sys';        //当前控制器所属模块

    public function __construct(SaasCustomerBalanceLogRepository $balanceLogRepository,DmsAgentInfoRepository $dmsAgentInfoRepository)
    {
        parent::__construct();
        $this->merchantID = empty(session('admin')) == false ? session('admin')['mch_id'] : ' ';
        $this->repositories = $balanceLogRepository;
        $this->dmsAgentInfoRepository = $dmsAgentInfoRepository;
    }

    public function index()
    {
        $user_id = '';
        $str = '';
        if(!empty(\request()->get('user_id'))){
            $user_id = \request()->get('user_id');
            $str = "?user_id=".$user_id;
        }
        //获取商家下的分销商
        $agent = $this->dmsAgentInfoRepository->getAgentList($this->merchantID);
        return view("merchant.agent.fund.index",['agent'=>$agent,'user_id'=>$user_id,'str'=>$str]);
    }

    //ajax方式获取列表
    public function table(Request $request)
    {
        $inputs = $request->all();
        $list = $this->repositories->getMerchantTableList($inputs,'created_at desc');
        $htmlContents = $this->renderHtml('',['list' =>$list]);
        $list = $list->toArray();
        $total = $list['total'];
        return $this->jsonSuccess(['html' => $htmlContents,'total' => $total]);
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
                $row = $this->repositories->fundAgentDetail($request->input('id'));
                $htmlContents = $this->renderHtml($this->viewPath.'.'.$this->form, ['row' => $row]);
                return $this->jsonSuccess(['html' => $htmlContents]);
            }
        } catch (CommonException $e) {
            return $this->jsonFailed($e->getMessage());
        }
    }

    //导出
//    public function fundExport(Request $request)
//    {
//        try{
//            $param = $request->data;
//            $this->repositories->export(json_decode($param,true));
//
//        }catch (CommonException $e){
//            return $this->jsonFailed($e->getMessage());
//        }
//    }

}