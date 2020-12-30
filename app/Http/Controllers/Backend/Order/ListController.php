<?php
namespace App\Http\Controllers\Backend\Order;

use App\Exceptions\CommonException;
use App\Http\Controllers\Merchant\BaseController;
use App\Repositories\SaasCompoundQueueRepository;
use App\Repositories\SaasDeliveryRepository;
use App\Repositories\SaasOrdersRepository;
use App\Repositories\SaasOrderTagRepository;
use App\Services\Exception;
use Illuminate\Http\Request;

/**
 * CMS订单列表
 *
 * @author: cjx
 * @version: 1.0
 * @date: 2020/06/28
 */
class ListController extends BaseController
{
    protected $viewPath = 'backend.order.list';  //当前控制器所的view所在的目录
    protected $modules = 'sys';        //当前控制器所属模块
    protected $mchID;

    public function __construct(SaasOrdersRepository $ordersRepository,SaasDeliveryRepository $saasDeliveryRepository,SaasOrderTagRepository $orderTagRepository,SaasCompoundQueueRepository $compoundQueueRepository)
    {
        parent::__construct();
        $this->repositories = $ordersRepository;
        $this->deliveryRepositories = $saasDeliveryRepository;
        $this->tagRepositories = $orderTagRepository;
        $this->compoundQueueRepository = $compoundQueueRepository;
        $this->mchID = PUBLIC_CMS_MCH_ID;
    }

    //列表展示页面
    public function index()
    {
        $setting = $this->getSetting();
        $pageLimit = $setting['default_pages_limit'];
        $chanel_list = $this->repositories->getSalesChanel();
        return view("backend.order.list.index",['pageLimit'=>$pageLimit,'chanelList'=>$chanel_list]);
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

    public function detail(Request $request)
    {
        $id = $request->id;
        $data = $this->repositories->orderInfo($id);

        return view("backend.order.list.detail",['data'=>$data]);
    }

    //取消订单
    public function cancelOrder(Request $request)
    {
        try{
            try{
                \DB::beginTransaction();

                $order_id = $request->id;
                $res = $this->repositories->cancelOrder($order_id,session("admin")["cms_adm_username"],config('common.sys_abbreviation')['backend'],session("admin")["cms_adm_id"]);

                if($res){
                    \DB::commit();
                    return $this->jsonSuccess('订单取消成功');
                }
            } catch (\Exception $e) {
                \DB::rollBack();
                if(!empty($e->getMessage())){
                    return $this->jsonFailed($e->getMessage());
                }else{
                    //订单取消出错
                    app(\App\Services\Exception::class)->throwException('70084',__FILE__.__LINE__);
                }
            }

        }catch (CommonException $e){
            return $this->jsonFailed($e->getMessage());
        }
    }

    //修改收货人信息
    public function changeInfo(Request $request)
    {
        $order_id = $request->id;
        try {
            if($request->post())
            {
                $param = $request->all();
                $param['id'] = $order_id;
                $param['order_rcv_province'] = $param['province'];
                $param['order_rcv_city'] = $param['city'];
                $param['order_rcv_area'] = $param['district'];

                unset($param['_token']);
                unset($param['province']);
                unset($param['city']);
                unset($param['district']);

                $res = $this->repositories->save($param);
                if($res){
                    return $this->jsonSuccess('收货人信息修改成功');
                }

            }else{
                $data = $this->repositories->getOrderInfo($order_id);
                $htmlContents = $this->renderHtml('backend.order.list.change_info',['data'=>$data]);

                return $this->jsonSuccess(['html' => $htmlContents]);
            }
        } catch (CommonException $e) {
            return $this->jsonFailed($e->getMessage());
        }

    }

    //修改物流
    public function changeDelivery(Request $request)
    {
        $order_id = $request->id;
        try {
            if($request->post())
            {
                $param = $request->all();
                $param['id'] = $order_id;
                unset($param['_token']);

                $res = $this->repositories->save($param);
                if($res){
                    return $this->jsonSuccess('物流方式修改成功');
                }

            }else{
                $data = $this->repositories->getOrderInfo($order_id);
                $delivery_list = $this->deliveryRepositories->getList(['delivery_status'=>1]);
                $htmlContents = $this->renderHtml('backend.order.list.change_delivery',['data'=>$data,'deliveryList'=>$delivery_list]);

                return $this->jsonSuccess(['html' => $htmlContents]);
            }
        } catch (CommonException $e) {
            return $this->jsonFailed($e->getMessage());
        }
    }

    //订单发货
    public function delivery(Request $request)
    {
        $order_id = $request->id;
        try{
            try {
                if($request->post())
                {
                    \DB::beginTransaction();

                    $param = $request->all();
                    unset($param['_token']);

                    $res = $this->repositories->delivery($order_id,$param,session("admin")['cms_adm_username'],config('common.sys_abbreviation')['backend']);
                    if($res){
                        \DB::commit();
                        return $this->jsonSuccess('订单发货成功');
                    }
                }else{
                    //商品、作品数据
                    $prod_data = $this->repositories->productAndWork($order_id);

                    //快递list
                    $delivery_list = $this->deliveryRepositories->getDelivery();

                    //订单数据
                    $order_info = $this->repositories->getOrderInfo($order_id);

                    $htmlContents = $this->renderHtml('backend.order.list.delivery',['productData'=>$prod_data,'deliveryList'=>$delivery_list,'orderInfo'=>$order_info]);
                    return $this->jsonSuccess(['html' => $htmlContents]);
                }
            } catch (\Exception $e) {
                \DB::rollBack();
                if(!empty($e->getMessage())){
                    return $this->jsonFailed($e->getMessage());
                }else{
                    //订单发货失败
                    app(\App\Services\Exception::class)->throwException('70036',__FILE__.__LINE__,'','','',['order_id'=>$order_id]);

                }
            }
        }catch (CommonException $exception){
            return $this->jsonFailed($exception->getMessage());
        }
    }

    //设置标签
    public function orderTag(Request $request)
    {
        $order_id = $request->id;
        try{
            try{
                if($request->post())
                {
                    \DB::beginTransaction();

                    $param = $request->all();

//                    if (count($param) == 1){
//                        //请选择订单标签
//                        return $this->jsonFailed('请选择订单标签');
//                    }

                    $res = $this->repositories->setTag($order_id,$param);
                    if($res){
                        \DB::commit();
                        return $this->jsonSuccess('设置成功',202);
                    }
                }else{
                    $tag_list = $this->tagRepositories->getTagList();

                    if(strpos($order_id,'[') !== false){
                        //批量标记
                        $htmlContents = $this->renderHtml('backend.order.list.tag',['order_id'=>$order_id,'tag_list'=>$tag_list]);
                    }else{
                        //单个标记
                        $order_info = $this->repositories->getById($order_id);
                        $tag_arr = explode(',',$order_info['order_tag_id']);
                        $htmlContents = $this->renderHtml('backend.order.list.tag',['order_id'=>$order_id,'tag_list'=>$tag_list,'tag'=>$tag_arr]);
                    }

                    return $this->jsonSuccess(['html' => $htmlContents]);
                }
            } catch (\Exception $e) {
                \DB::rollBack();
                if(!empty($e->getMessage())){
                    return $this->jsonFailed($e->getMessage());
                }else{
                    //订单标签设置出错
                    app(\App\Services\Exception::class)->throwException('70049',__FILE__.__LINE__);

                }
            }
        } catch (CommonException $e) {
            return $this->jsonFailed($e->getMessage());
        }
    }

    //手动提交生产
    public function production(Request $request)
    {
        $order_id = $request->id;
        try {
            try{
                if($request->post())
                {
                    \DB::beginTransaction();

                    $res = $this->repositories->submitProduction($order_id,session("admin")['cms_adm_username'],config('common.sys_abbreviation')['backend']);
                    if($res){
                        \DB::commit();
                        return $this->jsonSuccess('提交生产成功,已将订单加入队列处理，请稍后再操作');
                    }
                }else{
                    $data = $this->repositories->productAndWork($order_id);
                    $htmlContents = $this->renderHtml('backend.order.list.production',['data'=>$data]);
                    return $this->jsonSuccess(['html' => $htmlContents]);
                }
            } catch (\Exception $e) {
                \DB::rollBack();
                if(!empty($e->getMessage())){
                    return $this->jsonFailed($e->getMessage());
                }else{
                    //订单提交生产出错
                    app(\App\Services\Exception::class)->throwException('70073',__FILE__.__LINE__);

                }
            }
        } catch (CommonException $e) {
            return $this->jsonFailed($e->getMessage());
        }
    }

    //审核文件
    public function checkFile(Request $request)
    {
        try{
            $project_no = $request->number;
            $data = $this->repositories->checkFile($project_no);

            return view('backend.order.list.check',['data'=>$data,'project_no'=>$project_no]);
        }catch (CommonException $e) {
            echo $e->getMessage();
            die;
        }
    }

    //重新出图
    public function reloadImg(Request $request)
    {
        try{
            $project_no = $request->all('project_no');
            $result = $this->compoundQueueRepository->update(['project_sn'=>$project_no],['comp_queue_status'=>'ready']);

            if($result==1){
                return $this->jsonSuccess('');
            }else{
                return $this->jsonFailed('操作出错');
            }
        }catch (CommonException $e) {
            return $this->jsonFailed($e->getMessage());
        }
    }

    //下载前检查文件
    public function downloadCheck(Request $request)
    {
        try{
            $param = $request->all();
            $res = $this->repositories->downloadFileCheck($param['ord_prod_id']);

            return $this->jsonSuccess($res);

        }catch (CommonException $e){
            return $this->jsonFailed($e->getMessage());
        }
    }

    //下载文件
    public function downloadFile(Request $request)
    {
        ini_set('memory_limit', '512M');
        $arr = $request->all();

        if(isset($arr['url']) && !empty($arr['url'])){
            //能打开文件即可开始下载
            $this->repositories->startDownload($arr['url']);
        }else{
            echo '出错了';
            die;
        }
    }

    //封面内页下载
    public function insideCover(Request  $request)
    {
        try{
            $param = $request->all();
            if(isset($param['url']) && !empty($param['url'])){
                //能打开文件即可开始下载
                $this->repositories->startDownload($param['url']);
            }else{
                echo '下载出错';
                die;
            }
        }catch (CommonException $e) {
            echo ($e->getMessage());
            die;
        }
    }

    //订单导出
    public function orderExport(Request $request)
    {
        try{
            $param = $request->data;
            $this->repositories->export(json_decode($param,true));

        }catch (CommonException $e){
            return $this->jsonFailed($e->getMessage());
        }
    }

    //物流信息
    public function logistics(Request $request)
    {
        try{
            $order_id = $request->id;
            $data = $this->repositories->getLogistics($order_id);

            $htmlContents = $this->renderHtml('backend.order.list.logistics',['data'=>$data]);
            return $this->jsonSuccess(['html' => $htmlContents]);

        }catch (CommonException $e) {
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