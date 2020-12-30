<?php
namespace App\Services\Outer\Erp;

use App\Exceptions\CommonException;
use App\Http\Controllers\Api\BaseController;
use App\Models\DmsAgentInfo;
use App\Models\SaasAreas;
use App\Models\SaasDelivery;
use App\Models\SaasDownloadQueue;
use App\Models\SaasExpress;
use App\Models\SaasOrderErpPushQueue;
use App\Models\SaasOrderProduceQueue;
use App\Models\SaasOrderProducts;
use App\Models\SaasOrders;
use App\Models\SaasOuterErpOrderCreate;
use App\Models\SaasOuterErpOrderCreateQueue;
use App\Models\SaasProducts;
use App\Models\SaasProductsSku;
use App\Models\SaasSuppliers;
use App\Repositories\AreasRepository;
use App\Repositories\DmsAgentInfoRepository;
use App\Repositories\SaasAreasRepository;
use App\Repositories\SaasDeliveryRepository;
use App\Repositories\SaasExpressRepository;
use App\Repositories\SaasOrdersRepository;
use App\Repositories\SaasProductsRepository;
use App\Repositories\SaasSalesChanelRepository;
use App\Services\Helper;
use App\Services\Orders\OrdersEntity;
use App\Services\Orders\Production;
use App\Services\Orders\Status;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\EventListener\ValidateRequestListener;

/**
 * 外协订单创建自动任务
 * @author: hlt
 * @version: 1.0
 * @date: 2020/7/30
 */

class OuterOrderCreate
{


    //跑外部创建订单队列
    public function outerReadyErpOrderQueue()
    {
        $erpOrderCreateModel      = app(SaasOuterErpOrderCreate::class);
        $erpOrderCreateQueueModel = app(SaasOuterErpOrderCreateQueue::class);
        //获取限制条数
        $limit = config('queue_limit.outer_create_order_queue');
        $createInfo = $erpOrderCreateQueueModel->with(['createQueue'])->where(['status' => 'ready'])->limit($limit)->get()->toArray();
        //更改状态为progress
        foreach ($createInfo as $k => $v)
        {
            $erpOrderCreateQueueModel->where(['outer_queue_id' => $v['outer_queue_id']])->update(['status' => 'progress','start_time'=>time()]);
        }
        $this->runOuterErpOrder($createInfo);

    }
    //解析外协传过来的订单流水号获取订单号
    public function parseErpOrderItemNo($itemNo)
    {
        $em_arr = ['_','-'];
        foreach ($em_arr as $ke=>$ve){
            if (strstr( $itemNo ,$ve ) !== false){
                $oArray = explode($ve,$itemNo);
                //获取订单号
                $orderNo = $oArray[0];
                return $orderNo;
            }
        }
    }
    //解析外协传过来的订单流水号获取订单项目号
    public function parseErpOrderToItemNo($itemNo)
    {
        $em_arr = ['_','-'];
        foreach ($em_arr as $ke=>$ve){
            if (strstr( $itemNo ,$ve ) !== false){
                $oArray = explode($ve,$itemNo);
                if (count($oArray) != 3){
                    array_pop($oArray);
                }
                //获取订单项目号
                $orderItemNo = implode('-',$oArray);
                return $orderItemNo;
            }
        }
    }

    //跑外协创建生产订单队列
    public function runOuterErpOrder($createInfo)
    {
        $productsModel = app(SaasProducts::class);
        $dmsAgentInfoRepository = app(DmsAgentInfoRepository::class);
        $saleChannleRepository = app(SaasSalesChanelRepository::class);
        $entity = app(OrdersEntity::class);
        $orderModel = app(SaasOrders::class);
        $orderProductsModel = app(SaasOrderProducts::class);
        $statusService = app(Status::class);
        $downQueueModel = app(SaasDownloadQueue::class);
        $orderProduceModel = app(SaasOrderProduceQueue::class);
        $erpOrderCreateQueueModel = app(SaasOuterErpOrderCreateQueue::class);
        $produceService = app(Production::class);
        $erpPushModel = app(SaasOrderErpPushQueue::class);
        $createModel = app(SaasOuterErpOrderCreate::class);

        foreach ($createInfo as $k => $v)
        {
            try{
                try {
                    $createArr = $v['create_queue'];
                    $newOrderNo = "";
                    $newOrderItemNo = "";
                    //判断是否需要创建新订单
                    if ($v['is_new_order']!=ZERO) {
                        //新订单
                        //获取该商品的快递模板id
                        $shipping_temp_id = $productsModel->where('prod_id', $createArr['goods_id'])->value('prod_express_tpl_id');
                        if (empty($shipping_temp_id)) {
                            //无物流模板
                            $shipping_temp_id = ZERO;
                            //获取固定运费
                            $postFee = $productsModel->where('prod_id', $createArr['goods_id'])->value('prod_express_fee');
                        }

                        //获取合作代码
                        $partnerCode = $dmsAgentInfoRepository->getCodeById($createArr['agent_id']);
                        //支付方式id(余额)
                        $order_pay_id = config('common.order_pay_id');
                        //获取渠道
                        $chaId = $saleChannleRepository->getAgentChannleId();


                        //组织创建订单的数据
                        $items[$k] = [
                            'goods_id' => $createArr['goods_id'],
                            'product_id' => $createArr['sku_id'],
                            'works_id' => ZERO,
                            'file_type' => WORKS_FILE_TYPE_UPLOAD,
                            'file_info' => [
                                'file_url' => $createArr['file_url'],  //封面||内页||封底这样排
                            ],
                            'price_mod' => 1,                                      //1正常按本/个计价 2按张数计价
                            'buy_num' => $createArr['goods_num'],                         //购买数量 必须
                        ];
                        $receiver_info = [
                            'consignee' => $createArr['consignee'],         //必须 收货人
                            'ship_mobile' => $createArr['ship_mobile'],       //必须 收货人电话
                            'province_code' => $createArr['province'],     //省id
                            'city_code' => $createArr['city'],         //市id
                            'district_code' => $createArr['district'],     //区id
                            'ship_addr' => $createArr['ship_addr'],         //收货地址
                            'ship_tel' => $createArr['ship_mobile'],          //电话
                            'ship_zip' => $createArr['ship_zip'],          //邮编
                        ];
                        if (empty($shipping_temp_id)) {
                            $post_data['post_fee'] = $postFee;
                        }
                        $post_data = [
                            'items' => $items,
                            'receiver_info' => $receiver_info,
                            'outer_order_no' => "",                                                   //关联的第三方单号 选填
                            'shipping_temp_id' => $shipping_temp_id,                          //快递模板id 必须
                            'shipping_id' => $createArr['express_id'],                               //快递id 必须
                            'partner_code' => $partnerCode,                                        //合作代码，以些代码开头生成订单号
                            'mch_id' => $createArr['mch_id'],                            //商家id,必须
                            'chanel_id' => $chaId,                                             //渠道id,必须
                            'buyer_type' => CHANEL_TERMINAL_AGENT,                               // 终端用户类型 1代表分销 2代表会员，其他无效 必须
                            'user_id' => $createArr['agent_id'],              //用户id,必须
                            'note' => "",                                                   //用户备注  选填
                            //支付信息
                            'pay_info' => [                                                      //支付信息 必填
                                'pay_id' => $order_pay_id,                                           //余额、支付宝、微信等支付对应的id 必须
                            ],
                        ];
                        //请求订单创建接口
                        \DB::beginTransaction();
                        $res = $entity->create($post_data);
                        if ($res['status'] == 'success') {
                            $newOrderNo = $res['data'];
                            //创建成功
                            //添加订单的erp流水号
                            $orderModel->where('order_no', $newOrderNo)->update(['erp_order_serial_no' => $createArr['outer_order_no']]);
                            //修改订单状态
                            $orderId = $orderModel->where('order_no', $newOrderNo)->value('order_id');
                            //获取订单详情表的作品处理状态
                            $orderProductsModel->where('order_no',$newOrderNo)->update(['pro_handel_type' => WORKS_HANDEL_TYPE_PROCESSED,'sp_id'=>$createArr['sp_id']]);
                            $statusService->updateToProducing($orderId);
                            //生成erp推送队列记录
                            $insertPushData = [
                                'mch_id' => $createArr['mch_id'],
                                'order_id' => $orderId,
                                'order_push_status' => 'finish',
                                'times' => 1,
                                'start_time' => time(),
                                'end_time' => time(),
                                'created_at' => time(),
                            ];
                            $erpPushModel->insert($insertPushData);
                            //修改稿件下载队列状态
                            $downQueueModel->where('order_no', $newOrderNo)->update(['down_status' => 'finish', 'start_time' => time(), 'end_time' => time()]);
                            //修改生产队列状态
                            $orderProduceModel->where('order_id', $orderId)->update(['produce_queue_status' => 'finish', 'start_time' => time(), 'end_time' => time()]);
                            //获取订单项目号
                            $newOrderItemNo = $orderProductsModel->where('order_no',$newOrderNo)->value('ord_prj_item_no');

                        } else {
                            throw new \Exception($res['msg']);
                        }
                    }
                    //获取订单详情号跑生产后续流程
                    if (!isset($newOrderItemNo) || empty($newOrderItemNo)){
                        //解析外协传过来订单号得到项目号
                        $newOrderItemNo = $this->parseErpOrderToItemNo($v['outer_order_no']);
                    }
                    //获取订单号跑生产后续流程
                    if (empty($newOrderNo)){
                        //解析外协传过来订单号得到订单号
                        $newOrderNo = $this->parseErpOrderItemNo($v['outer_order_no']);
                    }
                    //获取该订单的供货商id
                    $spId = $createModel->where('outer_order_id',$v['order_create_id'])->value('sp_id');
                    //获取外协订单号
                    $erpOrderNo = $createModel->where('outer_order_id',$v['order_create_id'])->value('erp_order_no');
                    //获取该订单的分销id
                    $agentId = $createModel->where('outer_order_id',$v['order_create_id'])->value('agent_id');

                    //跑生产后续流程 传入订单号于订单项目号
                    $produceService->startProcessing($newOrderNo,$newOrderItemNo,$spId,$erpOrderNo,$agentId);
                    //成功
                    $newTime = ++$v['times'];
                    $erpOrderCreateQueueModel->where('outer_queue_id',$v['outer_queue_id'])->update(['status'=>'finish','err_msg'=>'','new_order_no'=>$newOrderNo,'times'=>$newTime,'end_time'=>time()]);

                    \DB::commit();
                } catch (\Exception $exception){
                    \DB::rollBack();
                    throw new CommonException($exception->getMessage(),$exception->getCode(),false,'all',[__FILE__.__LINE__.$exception->getMessage()]);
                }
            }catch (CommonException $e) {
                //队列错误
                //成功
                $newTime = ++$v['times'];
                $erpOrderCreateQueueModel->where('outer_queue_id',$v['outer_queue_id'])->update(['status'=>'error','err_msg'=>$e->getMessage(),'times'=>$newTime,'end_time'=>time()]);
            }


        }
    }
}