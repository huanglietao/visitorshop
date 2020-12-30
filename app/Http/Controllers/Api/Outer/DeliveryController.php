<?php
namespace App\Http\Controllers\Api\Outer;

use App\Exceptions\CommonException;
use App\Http\Controllers\Api\BaseController;
use App\Models\SaasNewSuppliersOrders;
use App\Models\SaasOrderProducts;
use App\Models\SaasOuterErpOrderCreateQueue;
use App\Repositories\SaasOrdersRepository;
use App\Services\Helper;
use App\Services\Outer\Delivery\Tmall;
use App\Services\Outer\Erp\Api;
use Illuminate\Http\Request;

/**
 * 外协发货相关接口
 *
 * @author: cjx
 * @version: 1.0
 * @date: 2020/06/30
 */
class DeliveryController extends BaseController
{

    /**
     * 外协订单发货回写
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function writeBack(Request $request)
    {
        try {
            $param = $request->input();

            //参数检查
            $this->checkData($param);

            //组装请求数据
            $post_data = $this->getData($param);

            if(!is_array($post_data)){
                //非外协订单直接返回成功
                return json_encode(["code"=>1,"message"=>"成功"]);
            }

            //请求发货接口
            $result = app(Api::class)->request(config('erp.interface_url').config('erp.outer_delivery_write_back'),$post_data);
            return $result;

        }catch (CommonException $e) {
            return $this->error($e->getCode(), $e->getMessage());
        }
    }

    /**
     * 订单相关参数检查
     * @param $param 接口参数
     */
    public function checkData($param)
    {
        //Repository
        $ordersRepository = app(SaasOrdersRepository::class);

        $order_no = isset($param['order_no']) ? $param['order_no'] : '';
        $express_num = isset($param['express_num']) ? $param['express_num'] : '';
        $express_type = isset($param['express_type']) ? $param['express_type'] : '';

        if(empty($order_no)){
            //缺少必要参数
            Helper::EasyThrowException(10023,__FILE__.__LINE__);
        }

        if(empty($express_num)){
            //快递单号不能为空
            Helper::EasyThrowException(70094,__FILE__.__LINE__);
        }

        if(empty($express_type)){
            //物流方式不能为空
            Helper::EasyThrowException(70095,__FILE__.__LINE__);
        }

        $order_info = $ordersRepository->getOrderInfo('',$order_no);

        if(empty($order_info)){
            //该订单记录不存在
            Helper::EasyThrowException(70030,__FILE__.__LINE__);
        }

    }

    /**
     * 组装请求数据
     * @param $order_no 订单号
     * @return array
     */
    public function getData($param)
    {
        //RepositoryAndModel
        $outerOrderCreateQueueModel = app(SaasOuterErpOrderCreateQueue::class);
        $newSpOrderRepository = app(SaasNewSuppliersOrders::class);

        $is_erp_order = $outerOrderCreateQueueModel->where(['new_order_no'=>$param['order_no']])->first();
        if(empty($is_erp_order)){
            //非外协订单直接返回成功
            return true;
        }

        //外协订单信息
        $outer_order_info = $outerOrderCreateQueueModel->where(['new_order_no'=>$param['order_no']])->select('outer_order_no','is_new_order')->first();
        if(empty($outer_order_info)){
            //外协订单不存在
            Helper::EasyThrowException(70096,__FILE__.__LINE__);
        }

        $data['partner_number'] = explode('_',$outer_order_info['outer_order_no'])[0];
        $data['express_num'] = $param['express_num'];
        $data['express_type'] = $param['express_type'];

        if($outer_order_info['is_new_order'] == PUBLIC_NO){
            //电商外协订单
            $ord_prod_info = $newSpOrderRepository->where(['order_no'=>$param['order_no']])->select('ord_prj_no','sp_num')->get()->toArray();

            foreach ($ord_prod_info as $k=>$v){
                $str = $v['ord_prj_no'].'_'.$v['sp_num'];
                $ord_prod_info[$k]['ord_prj_no'] = str_replace('-','_',$str);
            }
            $data['tripartite_serial_number_list'] = json_encode(array_column($ord_prod_info,'ord_prj_no'));
        }
        return $data;
    }

    /**
     * 订单手动发货回写
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function manualWriteBack(Request $request)
    {
        $param = $request->input();

        $post_data = $this->manualDeliveryData($param);

        file_put_contents('/tmp/outer_erp_order_delivery.log',var_export($post_data,true),FILE_APPEND);

        //请求发货接口
        $result = app(Api::class)->request(config('erp.interface_url').config('erp.outer_delivery_write_back'),$post_data);

        file_put_contents('/tmp/outer_erp_order_delivery.log',var_export($result,true),FILE_APPEND);

    }

    /**
     * 组装手动发货数据
     * @param $order_no 订单号
     * @return array
     */
    public function manualDeliveryData($param)
    {
        $newSpOrderRepository = app(SaasNewSuppliersOrders::class);

        if(empty($param['order_no'])){
            //缺少必要参数
            Helper::EasyThrowException(10023,__FILE__.__LINE__);
        }

        $data = [
            'partner_number'=>$param['order_no'] ?? '',
            'express_num'=>$param['delivery_code'] ?? '',
            'express_type'=>$param['delivery_name'] ?? '',
        ];

        $ord_prod_info = $newSpOrderRepository->where(['order_no'=>$param['order_no']])->select('ord_prj_no','sp_num')->get()->toArray();
        if(!empty($ord_prod_info)){
            foreach ($ord_prod_info as $k=>$v){
                $str = $v['ord_prj_no'].'_'.$v['sp_num'];
                $ord_prod_info[$k]['ord_prj_no'] = str_replace('-','_',$str);
            }
            $data['tripartite_serial_number_list'] = json_encode(array_column($ord_prod_info,'ord_prj_no'));
        }

        return $data;
    }

    /**
     * 手动回写淘宝发货(仅限爱美印旗舰店)
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function tbDeliveryWrite(Request $request)
    {
        $param = $request->input();

        $tmall = app(Tmall::class);

        file_put_contents('/tmp/outer_erp_order_delivery.log',var_export($param,true),FILE_APPEND);
        $res = $tmall->deliveryReturn($param['order_no'],$param['delivery_code'],'yto',18,20);
        file_put_contents('/tmp/outer_erp_order_delivery.log',var_export($res,true),FILE_APPEND);

    }
}