<?php
namespace App\Http\Controllers\Merchant\Suppliers;

use App\Http\Controllers\Merchant\BaseController;
use App\Http\Requests\Merchant\Suppliers\OrderPushRequest;
use App\Repositories\SaasOrderPushQueueRepository;
use Illuminate\Http\Request;

/**
 * 项目说明 OMS系统 供求管理--订单推送管理
 * 详细说明 OMS系统 供求管理--订单推送管理，实现列表，重新推送订单功能
 * @author: liujh <vali12138@163.com>
 * @version: 1.0
 * @date:  2020/04/10
 */
class OrderPushController extends BaseController
{
    protected $viewPath = 'merchant.suppliers.orderpush';  //当前控制器所的view所在的目录
    protected $modules = 'sys';        //当前控制器所属模块
    protected $merchantID = '';

    public function __construct(SaasOrderPushQueueRepository $Repository)
    {
        parent::__construct();
        $this->repositories = $Repository;
        $this->merchantID = empty(session('admin')) == false ? session('admin')['mch_id'] : ' ';
    }


    /**
     * ajax获取列表项
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function table(Request $request)
    {

        $inputs = $request->all();
        $inputs['mch_id']=$this->merchantID;
        $list = $this->repositories->getTableList($inputs);
        $htmlContents = $this->renderHtml('',['list' =>$list]);

        $pagesInfo = $list->toArray();

        $total = $pagesInfo['total'];

        return $this->jsonSuccess(['html' => $htmlContents,'total' => $total]);
    }


   //添加/编辑操作
    public function save(OrderPushRequest $request)
    {
        $ret = $this->repositories->save($request->all());
        if ($ret) {
            return $this->jsonSuccess([]);
        } else {
            return $this->jsonFailed('');
        }
    }

}
