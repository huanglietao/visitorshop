<?php
namespace App\Http\Controllers\Factory\Order;

use App\Exceptions\CommonException;
use App\Http\Controllers\Factory\BaseController;
use App\Repositories\SaasDeliveryRepository;
use App\Repositories\SaasNewSuppliersOrderRepository;
use App\Repositories\SaasOrdersRepository;
use App\Repositories\SaasSuppliersOrderRepository;
use App\Services\Exception;
use App\Services\Outer\Erp\Api;
use Illuminate\Http\Request;

/**
 * 供货商订单列表
 *
 * @author: cjx
 * @version: 1.0
 * @date: 2020/06/22
 */

class OrdersController extends BaseController
{
    protected $viewPath = 'factory.order.orders';  //当前控制器所的view所在的目录
    protected $modules = 'sys';        //当前控制器所属模块
    protected $mchID;
    protected $noLog = 'downloadFile';

    public function __construct(SaasNewSuppliersOrderRepository $Repository,SaasOrdersRepository $ordersRepository)
    {
        parent::__construct();
        $this->repositories = $Repository;
        $this->orderRepositories = $ordersRepository;
        $this->mchID = session('admin')['mch_id'];
    }

    //列表展示页面
    public function index()
    {
        $setting = $this->getSetting();
        $pageLimit = $setting['default_pages_limit'];
        $chanel_list = $this->orderRepositories->getSalesChanel();

        return view("factory.order.orders.index",['pageLimit'=>$pageLimit,'chanelList'=>$chanel_list]);
    }

    //ajax方式获取列表
    public function table(Request $request)
    {
        $inputs = $request->all();
        $list = $this->repositories->getTableList($inputs);
        $htmlContents = $this->renderHtml('',['list' =>$list['data']]);
        $total = $list['total'];

        return $this->jsonSuccess(['html' => $htmlContents,'total' => $total]);
    }

    //封面、内页下载
    public function downloadFile(Request $request)
    {
        ini_set('memory_limit', '512M');
        $param = $request->all();
        try{
            try {
                \DB::beginTransaction();

                $res = $this->repositories->download($param);
                if($res){
                    \DB::commit();
                }
            } catch (\Exception $e) {
                \DB::rollBack();
                if(!empty($e->getMessage())){
                    echo "出错了";die;
                }else{
                    //文件下载出错
                    app(\App\Services\Exception::class)->throwException('70076',__FILE__.__LINE__);

                }
            }
        }catch (CommonException $exception){
            echo "出错了";die;
        }
    }

    //更新订单状态
    public function changeStatus(Request $request)
    {
        try{
            \DB::beginTransaction();

            $param = $request->input();
            $data = $this->repositories->getById($param['new_sp_ord_id']);
            if($data['sp_order_status'] != SP_ORDER_STATUS_DELIVERY){
                //更新为已送货状态、生产状态(生产完成)
                $res = $this->repositories->update(['new_sp_ord_id'=>$param['new_sp_ord_id']],['sp_order_status'=>SP_ORDER_STATUS_DELIVERY,'new_sp_produce_status'=>ORDER_PRODUCED]);
                if($res){
                    \DB::commit();
                }
            }
            return $this->jsonSuccess('操作成功');

        }catch (CommonException $e){
            \DB::rollBack();
            return $this->jsonFailed($e->getMessage());
        }
    }

    //订单数量统计
    public function getCount()
    {
        $data = $this->repositories->orderStatusCount();
        return $this->jsonSuccess($data);
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