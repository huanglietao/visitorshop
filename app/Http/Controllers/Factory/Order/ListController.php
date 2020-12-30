<?php
namespace App\Http\Controllers\Factory\Order;

use App\Exceptions\CommonException;
use App\Http\Controllers\Factory\BaseController;
use App\Repositories\SaasDeliveryRepository;
use App\Repositories\SaasOrdersRepository;
use App\Repositories\SaasSuppliersOrderRepository;
use App\Services\Exception;
use Illuminate\Http\Request;

/**
 * 供货商订单列表
 *
 * @author: cjx
 * @version: 1.0
 * @date: 2020/05/15
 */

class ListController extends BaseController
{
    protected $viewPath = 'factory.order.list';  //当前控制器所的view所在的目录
    protected $modules = 'sys';        //当前控制器所属模块
    protected $mchID;

    public function __construct(SaasSuppliersOrderRepository $Repository,SaasOrdersRepository $ordersRepository,SaasDeliveryRepository $deliveryRepository)
    {
        parent::__construct();
        $this->repositories = $Repository;
        $this->orderRepositories = $ordersRepository;
        $this->deliveryRepositories= $deliveryRepository;
        $this->mchID = session('admin')['mch_id'];
    }

    //列表展示页面
    public function index()
    {
        $setting = $this->getSetting();
        $pageLimit = $setting['default_pages_limit'];
        $statusCount = $this->repositories->orderStatusCount();
        $chanel_list = $this->orderRepositories->getSalesChanel();

        return view("factory.order.list.index",['pageLimit'=>$pageLimit,'statusCount'=>$statusCount,'chanelList'=>$chanel_list]);
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

    //发货处理
    public function delivery(Request $request)
    {
        $sp_ord_id = $request->id;
        try{
            try {
                if($request->post())
                {
                    \DB::beginTransaction();

                    $param = $request->all();
                    $param['sp_ord_id'] = $sp_ord_id;
                    unset($param['_token']);

                    $res = $this->repositories->delivery($param);
                    if($res){
                        \DB::commit();
                        return $this->jsonSuccess('订单发货成功',202);
                    }
                }else{
                    //快递list
                    $delivery_list = $this->deliveryRepositories->getDelivery();

                    $htmlContents = $this->renderHtml('factory.order.list.delivery',['deliveryList'=>$delivery_list,'sp_ord_id'=>$sp_ord_id]);
                    return $this->jsonSuccess(['html' => $htmlContents]);
                }
            } catch (\Exception $e) {
                \DB::rollBack();
                if(!empty($e->getMessage())){
                    return $this->jsonFailed($e->getMessage());
                }else{
                    //订单发货失败
                    app(\App\Services\Exception::class)->throwException('70036',__FILE__.__LINE__,'','','',['sp_ord_ids'=>$sp_ord_id]);

                }
            }
        }catch (CommonException $exception){
            return $this->jsonFailed($exception->getMessage());
        }
    }

    //封面、内页下载
    public function downloadFile(Request $request)
    {
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