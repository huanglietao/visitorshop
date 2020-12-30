<?php
namespace App\Http\Controllers\Backend\Suppliers;

use App\Http\Controllers\Backend\BaseController;
use App\Http\Requests\Backend\Suppliers\OrderPushRequest;
use App\Repositories\SaasOrderPushQueueRepository;

/**
 * 项目说明 CMS系统 供应商管理--订单推送管理
 * 详细说明 CMS系统 供应商管理--订单推送管理，实现列表，重新推送订单功能
 * @author: liujh <vali12138@163.com>
 * @version: 1.0
 * @date:  2020/04/10
 */
class OrderPushController extends BaseController
{
    protected $viewPath = 'backend.suppliers.orderpush';  //当前控制器所的view所在的目录
    protected $modules = 'sys';        //当前控制器所属模块
    public function __construct(SaasOrderPushQueueRepository $Repository)
    {
        parent::__construct();
        $this->repositories = $Repository;
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