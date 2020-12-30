<?php
namespace App\Http\Controllers\Factory\Reportform;

use App\Exceptions\CommonException;
use App\Http\Controllers\Factory\BaseController;
use App\Repositories\SaasNewSuppliersOrderRepository;
use Illuminate\Http\Request;

/**
 * 供货商订单列表
 *
 * @author: david
 * @version: 1.0
 * @date: 2020/7/3
 */

class ProduceController extends BaseController
{
    protected $viewPath = 'factory.reportform.produce';  //当前控制器所的view所在的目录
    protected $modules = 'sys';        //当前控制器所属模块
    protected $supplierId;

    public function __construct(SaasNewSuppliersOrderRepository $Repository)
    {
        parent::__construct();
        $this->repositories = $Repository;

        $this->supplierId = session('admin')['sp_id'];
    }



    //ajax方式获取列表
    public function table(Request $request)
    {
        $inputs = $request->all();
        $inputs['sp_id'] = $this->supplierId; //加入默认该供货商
        //$inputs['sp_order_status'] = 5;

        $list = $this->repositories->getSendTableList($inputs);

        $htmlContents = $this->renderHtml('',['list' =>$list['data'],'orders_status'=>config('order.supplier_order_status')]);
        //转数组取总数量
        //$pagesInfo = $list->toArray();
        $total = $list['total'];

        return $this->jsonSuccess(['html' => $htmlContents,'total' => $total]);
    }

    //导出
    public function export(Request $request)
    {
        try{
            $param = $request->data;
            $params['send_time'] = implode('',json_decode($param,true));
            $params['sp_id'] = $this->supplierId; //加入默认该供货商
            //$params['sp_order_status'] = 5;

            $this->repositories->export($params);

        }catch (CommonException $e){
            return $this->jsonFailed($e->getMessage());
        }

    }

    /**
     * 返回成功的json
     * @param $data
     * @return array
     */
    protected function jsonSuccess($data,$status=201)
    {
        return response()->json(['status' =>$status , 'success' => 'true', 'data' => $data]);
    }

}