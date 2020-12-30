<?php
namespace App\Http\Controllers\Merchant\Order;

use App\Http\Controllers\Merchant\BaseController;
use App\Repositories\SaasExchangeRepository;
use Illuminate\Http\Request;

/**
 * 商户订单管理->换货单列表
 *
 * @author: cjx
 * @version: 1.0
 * @date: 2020/04/16
 */
class ExchangeController extends BaseController
{
    protected $viewPath = 'merchant.order.exchange';  //当前控制器所的view所在的目录
    protected $modules = 'sys';        //当前控制器所属模块

    public function __construct(SaasExchangeRepository $repository)
    {
        parent::__construct();
        $this->repositories = $repository;
    }

    //列表展示页面
    public function index()
    {
        $setting = $this->getSetting();
        $pageLimit = $setting['default_pages_limit'];
        return view("merchant.order.exchange.index",['pageLimit'=>$pageLimit]);
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
    protected function form(Request $request)
    {
        try {
            if($request->ajax())
            {
                $row = $this->repositories->exchangeDetail($request->input('id'));
                $htmlContents = $this->renderHtml($this->viewPath.'.'.$this->form, ['row' => $row]);
                return $this->jsonSuccess(['html' => $htmlContents]);
            }else{
                return view("admin.tips");
            }
        } catch (CommonException $e) {
            return $this->jsonFailed($e->getMessage());
        }

    }

}