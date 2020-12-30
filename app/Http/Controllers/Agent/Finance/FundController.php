<?php
namespace App\Http\Controllers\Agent\Finance;

use App\Exceptions\CommonException;
use App\Http\Controllers\Agent\BaseController;
use App\Repositories\SaasCustomerBalanceLogRepository;
use Illuminate\Http\Request;

/**
 * 资金明细
 * @author: cjx
 * @version: 1.0
 * @date: 2019/9/2
 */

class FundController extends BaseController
{
    protected $viewPath = 'agent.finance.fund';  //当前控制器所的view所在的目录
    protected $modules = 'sys';        //当前控制器所属模块

    public function __construct(SaasCustomerBalanceLogRepository $balanceLogRepository)
    {
        parent::__construct();
        $this->repositories = $balanceLogRepository;
    }

    public function index()
    {
        $setting = $this->getSetting();
        $pageLimit = $setting['default_pages_limit'];
        $statisticsInfo = $this->repositories->Statistics();
        return view("agent.finance.fund.index",['pageLimit'=>$pageLimit,'statisticsInfo'=>$statisticsInfo]);
    }

    //ajax方式获取列表
    public function table(Request $request)
    {
        $inputs = $request->all();
        $list = $this->repositories->getTableList($inputs)->toArray();
        $htmlContents = $this->renderHtml('',['list' =>$list['data']]);
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
                $row = $this->repositories->fundDetail($request->input('id'));

                $htmlContents = $this->renderHtml($this->viewPath.'.'.$this->form, ['row' => $row]);
                return $this->jsonSuccess(['html' => $htmlContents]);
            }
        } catch (CommonException $e) {
            return $this->jsonFailed($e->getMessage());
        }
    }

    //导出
    public function fundExport(Request $request)
    {
        try{
            $param = $request->data;
            $this->repositories->export(json_decode($param,true));

        }catch (CommonException $e){
            return $this->jsonFailed($e->getMessage());
        }
    }

}