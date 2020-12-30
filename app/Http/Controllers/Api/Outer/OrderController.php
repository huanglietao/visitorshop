<?php
namespace App\Http\Controllers\Api\Outer;

use App\Exceptions\CommonException;
use App\Http\Controllers\Api\BaseController;
use App\Repositories\SaasOrdersRepository;
use App\Services\Helper;
use App\Services\Orders\OrdersEntity;
use Illuminate\Http\Request;

/**
 * 稿件创建订单相关接口
 *
 * @author: cjx
 * @version: 1.0
 * @date: 2020/05/29
 */
class OrderController extends BaseController
{

    /**
     * @param Request $request
     * @param OrdersEntity $entity
     * @return \Illuminate\Http\JsonResponse
     */
    public function createOrder(Request $request, OrdersEntity $entity, SaasOrdersRepository $ordersRepository)
    {
        try {
            $param = $request->all();

            $data[] = $param;

            //参数检查
            $this->checkData($data);

            //记录请求数据
            file_put_contents('/tmp/outer_order_data.log',date("Y-m-d H:i:s"),FILE_APPEND);
            file_put_contents('/tmp/outer_order_data.log',print_r($param,1),FILE_APPEND);

            //稿件数据转化
            $res = $ordersRepository->manuscriptCreateOrder($data);

            //创建稿件订单
            $result = $entity->create($res);

            if($result['status'] == 'failed'){
                var_dump($result['msg']);
            }else{
                var_dump($result['data']);
            }

        }catch (CommonException $e) {
            return $this->error($e->getCode(), $e->getMessage());
        }
    }


    //订单必传参数检查
    public function checkData($data)
    {
        foreach ($data as  $k=>$v){
            if(empty($v['consignee']) || empty($v['ship_mobile'])){
                //联系人信息与手机号码必须
                Helper::EasyThrowException(70005,__FILE__.__LINE__);
            }

            if(empty($v['province'])){
                //收货人省份信息必须
                Helper::EasyThrowException(70006,__FILE__.__LINE__);
            }

            if(empty($v['city'])){
                //收货人城市信息必须
                Helper::EasyThrowException(70007,__FILE__.__LINE__);
            }

            if(empty($v['district'])){
                //收货人区信息必须
                Helper::EasyThrowException(70008,__FILE__.__LINE__);
            }

            if(empty($v['ship_addr'])){
                //详细地址必须
                Helper::EasyThrowException(70009,__FILE__.__LINE__);
            }

            if(empty($v['shipping_id'])){
                //快递方式必须
                Helper::EasyThrowException(70005,__FILE__.__LINE__);
            }

            if(empty($v['product_id'])){
                //货品id必须
                Helper::EasyThrowException(70005,__FILE__.__LINE__);
            }

            if(empty($v['works_url'])){
                //稿件信息必须
                Helper::EasyThrowException(70005,__FILE__.__LINE__);
            }

            if(empty($v['goods_number'])){
                //商品数量必须
                Helper::EasyThrowException(70005,__FILE__.__LINE__);
            }

//            if(!isset($v['folio_number'])){
//                //稿件页数必须
//                Helper::EasyThrowException(70005,__FILE__.__LINE__);
//            }

            if(empty($v['coop_code'])){
                //未配置合作编号
                Helper::EasyThrowException(70079,__FILE__.__LINE__);
            }

            if(empty($v['order_sn'])){
                //订单编号必须
                Helper::EasyThrowException(70005,__FILE__.__LINE__);
            }

        }
    }

}